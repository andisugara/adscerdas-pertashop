<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrganizationController extends Controller
{
    public function index()
    {
        $organizations = Auth::user()->organizations;
        return view('organizations.index', compact('organizations'));
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode_pertashop' => 'required|string|max:50|unique:organizations',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'harga_jual' => 'required|numeric|min:0',
            'rumus' => 'required|numeric|min:0',
            'hpp_per_liter' => 'required|numeric|min:0',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);
        $validated['is_active'] = false; // Inactive until subscription is paid

        $organization = Organization::create($validated);

        // Attach current user as owner
        $organization->users()->attach(Auth::id(), [
            'role' => 'owner',
            'is_active' => true,
        ]);

        // Set as active organization
        $user = Auth::user();
        $user->active_organization_id = $organization->id;
        $user->save();

        return redirect()->route('subscription.plans')
            ->with('success', 'Pertashop berhasil dibuat! Silakan pilih paket subscription untuk mengaktifkan.');
    }

    public function edit(Organization $organization)
    {
        // Check if user is owner of this organization
        if (!$organization->users->contains(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke pertashop ini.');
        }

        return view('organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        // Check if user is owner of this organization
        if (!$organization->users->contains(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke pertashop ini.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'kode_pertashop' => 'required|string|max:50|unique:organizations,kode_pertashop,' . $organization->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'harga_jual' => 'required|numeric|min:0',
            'rumus' => 'required|numeric|min:0',
            'hpp_per_liter' => 'required|numeric|min:0',
        ]);

        $organization->update($validated);

        return redirect()->route('organizations.index')
            ->with('success', 'Pertashop berhasil diperbarui!');
    }

    public function switchOrganization(Organization $organization)
    {
        // Check if user is member of this organization
        if (!$organization->users->contains(Auth::id())) {
            abort(403, 'Anda tidak memiliki akses ke pertashop ini.');
        }

        // Check if organization has active subscription or is in trial
        if (!$organization->isInTrial() && !$organization->hasActiveSubscription()) {
            return redirect()->route('organizations.index')
                ->withErrors(['error' => 'Organization ' . $organization->name . ' tidak memiliki subscription aktif. Silakan subscribe terlebih dahulu.']);
        }

        $user = Auth::user();
        $user->update(['active_organization_id' => $organization->id]);

        return redirect()->route('dashboard')
            ->with('success', 'Berhasil beralih ke ' . $organization->name);
    }
}
