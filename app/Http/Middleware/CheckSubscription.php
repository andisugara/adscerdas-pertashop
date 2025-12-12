<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Superadmin bypass subscription check
        if ($user && $user->isSuperadmin()) {
            return $next($request);
        }

        // Check if user has active organization
        if (!$user || !$user->active_organization_id) {
            return redirect()->route('organizations.select')
                ->with('error', 'Silakan pilih pertashop terlebih dahulu.');
        }

        $organization = $user->activeOrganization;

        // Check if organization exists
        if (!$organization) {
            return redirect()->route('organizations.select')
                ->with('error', 'Organization tidak ditemukan.');
        }

        // Check if user is member of this organization
        if (!$organization->users->contains($user->id)) {
            return redirect()->route('organizations.select')
                ->with('error', 'Anda tidak memiliki akses ke organization ini.');
        }

        // Check if in trial period
        if ($organization->isInTrial()) {
            return $next($request);
        }

        // Check if has active subscription
        if (!$organization->hasActiveSubscription()) {
            return redirect()->route('subscription.expired')
                ->with('error', 'Subscription untuk ' . $organization->name . ' telah berakhir. Silakan perpanjang untuk melanjutkan.');
        }

        return $next($request);
    }
}
