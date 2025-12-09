<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $query = Deposit::with(['user', 'shift']);

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if (Auth::user()->isOperator()) {
            $query->where('user_id', Auth::id());
        }

        $deposits = $query->latest('tanggal')->paginate(15);

        return view('deposits.index', compact('deposits'));
    }

    public function create()
    {
        $shifts = Shift::where('aktif', true)->orderBy('urutan')->get();
        return view('deposits.create', compact('shifts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        Deposit::create($validated);

        return redirect()->route('deposits.index')
            ->with('success', 'Setoran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $deposit = Deposit::with(['user', 'shift'])->findOrFail($id);
        return view('deposits.show', compact('deposit'));
    }

    public function edit(string $id)
    {
        $deposit = Deposit::findOrFail($id);

        if (Auth::user()->isOperator() && $deposit->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit setoran ini.');
        }

        $shifts = Shift::where('aktif', true)->orderBy('urutan')->get();
        return view('deposits.edit', compact('deposit', 'shifts'));
    }

    public function update(Request $request, string $id)
    {
        $deposit = Deposit::findOrFail($id);

        if (Auth::user()->isOperator() && $deposit->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit setoran ini.');
        }

        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        $deposit->update($validated);

        return redirect()->route('deposits.index')
            ->with('success', 'Setoran berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->isOperator()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus setoran.');
        }

        $deposit = Deposit::findOrFail($id);
        $deposit->delete();

        return redirect()->route('deposits.index')
            ->with('success', 'Setoran berhasil dihapus.');
    }

    private function parseDecimal($value)
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return $value;
    }
}
