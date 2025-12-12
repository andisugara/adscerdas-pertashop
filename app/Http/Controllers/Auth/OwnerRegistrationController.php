<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OwnerRegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        $trialDays = SystemSetting::get('trial_days', 14);
        $monthlyPrice = SystemSetting::get('monthly_price', 100000);
        $yearlyPrice = SystemSetting::get('yearly_price', 1000000);

        return view('auth.owner-register', compact('trialDays', 'monthlyPrice', 'yearlyPrice'));
    }

    public function register(Request $request)
    {
        // Normalize decimal inputs (convert comma to dot)
        $request->merge([
            'stok_awal' => str_replace(',', '.', $request->stok_awal),
            'totalisator_awal' => str_replace(',', '.', $request->totalisator_awal),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'stok_awal' => 'required|numeric|min:0',
            'totalisator_awal' => 'required|numeric|min:0',
            'plan' => 'required|in:trial,monthly,yearly',
            'payment_method' => 'required_if:plan,monthly,yearly|in:manual,duitku',
        ]);

        DB::beginTransaction();
        try {
            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'owner',
                'aktif' => true,
            ]);

            // Create organization with user's name
            $organization = Organization::create([
                'name' => $validated['name'] . ' Organization',
                'slug' => Str::slug($validated['name']) . '-' . Str::random(6),
                'phone' => '',
                'email' => $validated['email'],
                'address' => '',
                'stok_awal' => $validated['stok_awal'],
                'totalisator_awal' => $validated['totalisator_awal'],
                'is_active' => true,
            ]);

            // Attach user to organization as owner
            $organization->users()->attach($user->id, [
                'role' => 'owner',
                'is_active' => true,
            ]);

            // Create trial subscription
            $trialDays = (int) SystemSetting::get('trial_days', 14);
            \App\Models\Subscription::create([
                'organization_id' => $organization->id,
                'plan_name' => 'trial',
                'price' => 0,
                'status' => 'active',
                'payment_method' => null,
                'payment_status' => 'paid',
                'starts_at' => now(),
                'ends_at' => now()->addDays($trialDays),
                'approved_at' => now(),
                'approved_by' => null, // System generated
            ]);

            // Set active organization
            $user->update(['active_organization_id' => $organization->id]);

            // Create paid subscription if not trial
            $paidSubscription = null;
            if ($validated['plan'] !== 'trial') {
                $paidSubscription = $this->createSubscription($organization, $validated);
            }

            DB::commit();

            // Login user
            auth()->guard('web')->login($user);

            // Redirect based on plan
            if ($paidSubscription) {
                if ($validated['payment_method'] === 'duitku') {
                    return redirect()->route('subscription.duitku.payment', $paidSubscription)
                        ->with('success', 'Registration successful! Please complete the payment.');
                } else {
                    return redirect()->route('subscription.manual.payment', $paidSubscription)
                        ->with('success', 'Registration successful! Please upload your payment proof.');
                }
            }

            return redirect()->route('dashboard')
                ->with('success', 'Registration successful! Your trial period has started.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    private function createSubscription(Organization $organization, array $data)
    {
        $plan = $data['plan'];
        $paymentMethod = $data['payment_method'] ?? 'manual';

        // Only create paid subscriptions (trial is already created above)
        $price = $plan === 'monthly'
            ? (int) SystemSetting::get('monthly_price', 100000)
            : (int) SystemSetting::get('yearly_price', 1000000);

        $duration = (int) ($plan === 'monthly' ? 30 : 365);

        return Subscription::create([
            'organization_id' => $organization->id,
            'plan_name' => $plan,
            'price' => $price,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'starts_at' => now(),
            'ends_at' => now()->addDays($duration),
        ]);
    }
}
