<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $organizationId = $user->active_organization_id;

        $setting = Setting::where('organization_id', $organizationId)->first();

        if (!$setting) {
            // Create default setting if not exists for this organization
            $organization = $user->activeOrganization;
            $setting = Setting::create([
                'organization_id' => $organizationId,
                'nama_pertashop' => $organization->name,
                'kode_pertashop' => 'PTS' . rand(100, 999),
                'alamat' => $organization->address ?? '',
                'harga_jual' => 12000,
                'rumus' => 2.09,
                'hpp_per_liter' => 11500,
            ]);
        }

        return view('settings.index', compact('setting'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return redirect()->route('settings.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->route('settings.index');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_pertashop' => 'required|string|max:255',
            'kode_pertashop' => 'required|string|max:50',
            'alamat' => 'nullable|string',
            'harga_jual' => 'required|numeric|min:0',
            'rumus' => 'required|numeric|min:0',
            'hpp_per_liter' => 'required|numeric|min:0',
        ]);

        $organizationId = Auth::user()->active_organization_id;
        $setting = Setting::where('organization_id', $organizationId)->findOrFail($id);
        $setting->update($validated);

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
