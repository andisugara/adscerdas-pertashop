<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

        $setting = Setting::findOrFail($id);
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
