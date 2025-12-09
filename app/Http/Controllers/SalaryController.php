<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::orderBy('bulan', 'desc')->paginate(12);
        return view('salaries.index', compact('salaries'));
    }

    public function create()
    {
        return view('salaries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|unique:salaries,bulan',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Parse decimal format
        $jumlah = str_replace(['.', ','], ['', '.'], $request->jumlah);

        Salary::create([
            'bulan' => $request->bulan,
            'jumlah' => $jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('salaries.index')
            ->with('success', 'Data gaji berhasil ditambahkan');
    }

    public function edit(Salary $salary)
    {
        return view('salaries.edit', compact('salary'));
    }

    public function update(Request $request, Salary $salary)
    {
        $request->validate([
            'bulan' => 'required|unique:salaries,bulan,' . $salary->id,
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        // Parse decimal format
        $jumlah = str_replace(['.', ','], ['', '.'], $request->jumlah);

        $salary->update([
            'bulan' => $request->bulan,
            'jumlah' => $jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('salaries.index')
            ->with('success', 'Data gaji berhasil diupdate');
    }

    public function destroy(Salary $salary)
    {
        $salary->delete();

        return redirect()->route('salaries.index')
            ->with('success', 'Data gaji berhasil dihapus');
    }
}
