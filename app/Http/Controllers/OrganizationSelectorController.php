<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationSelectorController extends Controller
{
    /**
     * Show organization selector page.
     */
    public function select()
    {
        $user = Auth::user();

        // Superadmin bypass
        if ($user->isSuperadmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        $organizations = $user->organizations()->where('is_active', true)->get();

        // If user has no organizations, show error
        if ($organizations->isEmpty()) {
            return view('organizations.no-access');
        }

        // If user has only one organization, auto-select it
        if ($organizations->count() === 1) {
            $user->update(['active_organization_id' => $organizations->first()->id]);
            return redirect()->route('dashboard');
        }

        return view('organizations.select', compact('organizations'));
    }

    /**
     * Switch to selected organization.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
        ]);

        $user = Auth::user();

        if ($user->switchOrganization($request->organization_id)) {
            return redirect()->route('dashboard')
                ->with('success', 'Switched to ' . $user->activeOrganization->name);
        }

        return back()->with('error', 'You do not have access to this organization.');
    }

    /**
     * Show subscription expired page.
     */
    public function expired()
    {
        return view('subscription.expired');
    }
}
