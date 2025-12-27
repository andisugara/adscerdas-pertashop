<?php

namespace App\Http\Controllers;

use App\Models\TankAddition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TankAdditionController extends Controller
{
    public function index(Request $request)
    {
        $query = TankAddition::with('user');

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $tankAdditions = $query->latest('tanggal')->paginate(15);

        return view('tank-additions.index', compact('tankAdditions'));
    }

    public function create()
    {
        return view('tank-additions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_polisi' => 'nullable|string|max:50',
            'shipment_no' => 'nullable|string|max:100',
            'nama_pengemudi' => 'nullable|string|max:255',
            'no_so_sa' => 'nullable|string|max:100',
            'jumlah_liter' => 'required|string',
            'stok_awal' => 'nullable|string',
            'stok_akhir' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Convert format Indonesia ke database format
        $validated['jumlah_liter'] = $this->parseDecimal($validated['jumlah_liter']);
        
        if (isset($validated['stok_awal'])) {
            $validated['stok_awal'] = $this->parseDecimal($validated['stok_awal']);
        }
        
        if (isset($validated['stok_akhir'])) {
            $validated['stok_akhir'] = $this->parseDecimal($validated['stok_akhir']);
        }
        
        $validated['user_id'] = Auth::id();

        TankAddition::create($validated);

        return redirect()->route('tank-additions.index')
            ->with('success', 'Penambahan tangki berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $tankAddition = TankAddition::with('user')->findOrFail($id);
        return view('tank-additions.show', compact('tankAddition'));
    }

    public function edit(string $id)
    {
        $tankAddition = TankAddition::findOrFail($id);
        return view('tank-additions.edit', compact('tankAddition'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'no_polisi' => 'nullable|string|max:50',
            'shipment_no' => 'nullable|string|max:100',
            'nama_pengemudi' => 'nullable|string|max:255',
            'no_so_sa' => 'nullable|string|max:100',
            'jumlah_liter' => 'required|string',
            'stok_awal' => 'nullable|string',
            'stok_akhir' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        // Convert format Indonesia ke database format
        $validated['jumlah_liter'] = $this->parseDecimal($validated['jumlah_liter']);
        
        if (isset($validated['stok_awal'])) {
            $validated['stok_awal'] = $this->parseDecimal($validated['stok_awal']);
        }
        
        if (isset($validated['stok_akhir'])) {
            $validated['stok_akhir'] = $this->parseDecimal($validated['stok_akhir']);
        }

        $tankAddition = TankAddition::findOrFail($id);
        $tankAddition->update($validated);

        return redirect()->route('tank-additions.index')
            ->with('success', 'Penambahan tangki berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->isOperator()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
        }

        $tankAddition = TankAddition::findOrFail($id);
        $tankAddition->delete();

        return redirect()->route('tank-additions.index')
            ->with('success', 'Penambahan tangki berhasil dihapus.');
    }

    private function parseDecimal($value)
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return $value;
    }
}
