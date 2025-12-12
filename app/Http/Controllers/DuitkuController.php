<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuitkuController extends Controller
{
    private function getDuitkuConfig()
    {
        $isSandbox = SystemSetting::get('duitku_mode', 'sandbox') === 'sandbox';

        return [
            'merchant_code' => SystemSetting::get('duitku_merchant_code', $isSandbox ? 'D12974' : ''),
            'api_key' => SystemSetting::get('duitku_api_key', $isSandbox ? 'a89d85511f97ec20bd493308249ccaab' : ''),
            'callback_url' => SystemSetting::get('duitku_callback_url', url('/duitku/callback')),
            'sandbox' => $isSandbox,
        ];
    }

    private function getBaseUrl($config)
    {
        return $config['sandbox']
            ? 'https://sandbox.duitku.com/webapi/api/merchant'
            : 'https://passport.duitku.com/webapi/api/merchant';
    }

    private function getCheckoutUrl($config)
    {
        return $config['sandbox']
            ? 'https://app-sandbox.duitku.com/lib/js/duitku.js'
            : 'https://app-prod.duitku.com/lib/js/duitku.js';
    }

    public function createPayment(Subscription $subscription)
    {
        if ($subscription->organization_id !== auth()->user()->active_organization_id) {
            abort(403);
        }

        $config = $this->getDuitkuConfig();

        if (!$config['merchant_code'] || !$config['api_key']) {
            return redirect()->route('subscription.plans')
                ->withErrors(['error' => 'Duitku payment gateway not configured']);
        }

        // Always generate new merchant order ID to avoid signature mismatch
        // Reset previous duitku data if any
        $merchantOrderId = 'SUB-' . $subscription->id . '-' . time();

        $subscription->update([
            'merchant_order_id' => null,
            'duitku_reference' => null,
        ]);

        // Prepare payment data for Duitku
        $user = auth()->user();
        $organization = $subscription->organization;

        $paymentData = [
            'merchantCode' => $config['merchant_code'],
            'paymentAmount' => (int) $subscription->price,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Pertashop Subscription - ' . ucfirst($subscription->plan_name),
            'customerVaName' => $user->name,
            'email' => $user->email,
            'phoneNumber' => $organization->phone ?? '08123456789',
            'itemDetails' => [
                [
                    'name' => ucfirst($subscription->plan_name) . ' Subscription',
                    'price' => (int) $subscription->price,
                    'quantity' => 1,
                ]
            ],
            'customerDetail' => [
                'firstName' => $user->name,
                'email' => $user->email,
                'phoneNumber' => $organization->phone ?? '08123456789',
            ],
            'callbackUrl' => $config['callback_url'],
            'returnUrl' => route('subscriptions.index'),
            'expiryPeriod' => 1440, // 24 hours in minutes
        ];

        // Generate signature for Duitku Inquiry API
        // Format: MD5(merchantCode + merchantOrderId + paymentAmount + apiKey)
        $signatureString = $config['merchant_code'] . $merchantOrderId . $paymentData['paymentAmount'] . $config['api_key'];
        $signature = md5($signatureString);

        $paymentData['signature'] = $signature;

        Log::info('Duitku payment request', [
            'merchantCode' => $config['merchant_code'],
            'merchantOrderId' => $merchantOrderId,
            'amount' => $paymentData['paymentAmount'],
            'callbackUrl' => $config['callback_url'],
            'sandbox' => $config['sandbox'],
            'signatureString' => $signatureString,
            'signature' => $signature,
        ]);

        try {
            // Call Duitku API
            $response = Http::post($this->getBaseUrl($config) . '/inquiry', $paymentData);

            Log::info('Duitku API response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $result = $response->json();

                // Duitku inquiry returns reference and paymentUrl directly on success
                if (isset($result['reference'])) {
                    // Update subscription with Duitku reference
                    $subscription->update([
                        'merchant_order_id' => $merchantOrderId,
                        'duitku_reference' => $result['reference'],
                    ]);

                    Log::info('Duitku payment created successfully', [
                        'reference' => $result['reference'],
                        'merchantOrderId' => $merchantOrderId,
                    ]);

                    // Return view with Snap/Pop-up payment
                    return view('subscription.duitku-snap', [
                        'subscription' => $subscription,
                        'reference' => $result['reference'],
                        'merchantCode' => $config['merchant_code'],
                        'amount' => $paymentData['paymentAmount'],
                        'checkoutUrl' => $this->getCheckoutUrl($config),
                    ]);
                } else {
                    Log::error('Duitku payment creation failed', [
                        'result' => $result,
                    ]);
                    return redirect()->route('subscription.plans')
                        ->withErrors(['error' => 'Payment creation failed: ' . ($result['Message'] ?? 'Unknown error')]);
                }
            } else {
                Log::error('Duitku API error', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);
                return redirect()->route('subscription.plans')
                    ->withErrors(['error' => 'Payment gateway error. Please check configuration.']);
            }
        } catch (\Exception $e) {
            Log::error('Duitku exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('subscription.plans')
                ->withErrors(['error' => 'Payment system error: ' . $e->getMessage()]);
        }
    }

    public function callback(Request $request)
    {
        Log::info('Duitku callback received', $request->all());

        $config = $this->getDuitkuConfig();

        // Verify signature
        $signature = hash(
            'sha256',
            $config['merchant_code'] .
                $request->merchantOrderId .
                $request->resultCode .
                $config['api_key']
        );

        if ($signature !== $request->signature) {
            Log::error('Duitku callback signature mismatch', [
                'expected' => $signature,
                'received' => $request->signature,
                'data' => $request->all()
            ]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        // Find subscription by merchant order ID
        $subscription = Subscription::where('merchant_order_id', $request->merchantOrderId)->first();

        if (!$subscription) {
            Log::error('Duitku callback: subscription not found', ['merchantOrderId' => $request->merchantOrderId]);
            return response()->json(['message' => 'Subscription not found'], 404);
        }

        // Process payment based on result code
        if ($request->resultCode === '00') {
            // Payment successful - auto approve subscription
            $subscription->update([
                'status' => 'active',
                'payment_status' => 'paid',
                'duitku_reference' => $request->reference ?? $subscription->duitku_reference,
                'approved_at' => now(),
                'approved_by' => null, // Auto-approved by system via Duitku
            ]);

            // Activate organization if this is their first paid subscription
            $organization = $subscription->organization;
            if (!$organization->is_active) {
                $organization->update(['is_active' => true]);
            }

            Log::info('Duitku payment successful - subscription activated', [
                'subscription_id' => $subscription->id,
                'organization_id' => $organization->id,
                'merchantOrderId' => $request->merchantOrderId,
                'reference' => $request->reference,
            ]);
        } else {
            // Payment failed
            $subscription->update([
                'status' => 'cancelled',
                'payment_status' => 'failed',
            ]);

            Log::warning('Duitku payment failed', [
                'subscription_id' => $subscription->id,
                'resultCode' => $request->resultCode,
                'resultMessage' => $request->resultMessage ?? 'Unknown',
            ]);
        }

        return response()->json(['message' => 'Callback processed successfully']);
    }

    public function return(Request $request)
    {
        $merchantOrderId = $request->merchantOrderId;
        $subscription = Subscription::where('merchant_order_id', $merchantOrderId)->first();

        if (!$subscription) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Subscription not found']);
        }

        if ($subscription->payment_status === 'paid') {
            return redirect()->route('dashboard')
                ->with('success', 'Payment successful! Your subscription is now active.');
        } else {
            return redirect()->route('subscription.plans')
                ->withErrors(['error' => 'Payment verification pending. Please wait for confirmation.']);
        }
    }
}
