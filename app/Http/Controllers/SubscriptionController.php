<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $organization = $user->activeOrganization;

        if (!$organization) {
            return redirect()->route('organizations.index')
                ->with('error', 'Silakan pilih pertashop terlebih dahulu');
        }

        $subscriptions = Subscription::where('organization_id', $organization->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $activeSubscription = $organization->activeSubscription;
        $trialSubscription = $organization->trialSubscription;
        $isInTrial = $organization->isInTrial();

        return view('subscriptions.index', compact('subscriptions', 'activeSubscription', 'trialSubscription', 'isInTrial', 'organization'));
    }

    public function plans()
    {
        $trialDays = SystemSetting::get('trial_days', 14);
        $monthlyPrice = SystemSetting::get('monthly_price', 100000);
        $yearlyPrice = SystemSetting::get('yearly_price', 1000000);

        return view('subscription.plans', compact('trialDays', 'monthlyPrice', 'yearlyPrice'));
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'plan' => 'required|in:monthly,yearly',
            'payment_method' => 'required|in:manual,duitku',
        ]);

        $organization = auth()->user()->activeOrganization;

        if (!$organization) {
            return redirect()->route('organizations.select')
                ->withErrors(['error' => 'Please select an organization first']);
        }

        // Check if there's already a pending subscription
        $pending = Subscription::where('organization_id', $organization->id)
            ->where('status', 'pending')
            ->first();

        if ($pending) {
            return redirect()->route('subscription.plans')
                ->withErrors(['error' => 'You already have a pending subscription']);
        }

        $plan = $validated['plan'];
        $paymentMethod = $validated['payment_method'];

        $price = $plan === 'monthly'
            ? (int) SystemSetting::get('monthly_price', 100000)
            : (int) SystemSetting::get('yearly_price', 1000000);

        $duration = (int) ($plan === 'monthly' ? 30 : 365);

        $subscription = Subscription::create([
            'organization_id' => $organization->id,
            'plan_name' => $plan,
            'price' => $price,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'starts_at' => now(),
            'ends_at' => now()->addDays($duration),
        ]);

        if ($paymentMethod === 'duitku') {
            // Redirect to Duitku payment page
            return redirect()->action([DuitkuController::class, 'createPayment'], $subscription);
        }

        return redirect()->route('subscription.manual.payment', $subscription);
    }

    public function manualPayment(Subscription $subscription)
    {
        if ($subscription->organization_id !== auth()->user()->active_organization_id) {
            abort(403);
        }

        return view('subscription.manual-payment', compact('subscription'));
    }

    public function uploadProof(Request $request, Subscription $subscription)
    {
        if ($subscription->organization_id !== auth()->user()->active_organization_id) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $subscription->update([
            'payment_proof' => $path,
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu approval dari admin.');
    }
}
