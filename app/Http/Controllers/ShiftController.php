<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::orderBy('urutan')->get();
        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'urutan' => 'required|integer|min:1',
            'aktif' => 'boolean',
        ]);

        Shift::create($validated);
        return redirect()->route('shifts.index')
            ->with('success', 'Shift berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_shift' => 'required|string|max:255',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'urutan' => 'required|integer|min:1',
            'aktif' => 'boolean',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update($validated);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
