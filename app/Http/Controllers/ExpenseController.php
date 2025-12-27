<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('user');

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        $expenses = $query->latest('tanggal')->paginate(15);

        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pengeluaran' => 'required|string|max:255',
            'jumlah' => 'required|string',
            'keterangan' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $validated['jumlah'] = $this->parseDecimal($validated['jumlah']);
        $validated['user_id'] = Auth::id();

        if ($request->hasFile('bukti_pengeluaran')) {
            $validated['bukti_pengeluaran'] = $request->file('bukti_pengeluaran')
                ->store('expenses', 'public');
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $expense = Expense::with('user')->findOrFail($id);
        return view('expenses.show', compact('expense'));
    }

    public function edit(string $id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.edit', compact('expense'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'nama_pengeluaran' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_image' => 'nullable|boolean',
        ]);

        $expense = Expense::findOrFail($id);

        // Handle delete image checkbox
        if ($request->has('delete_image') && $request->delete_image) {
            if ($expense->bukti_pengeluaran && Storage::disk('public')->exists($expense->bukti_pengeluaran)) {
                Storage::disk('public')->delete($expense->bukti_pengeluaran);
            }
            $validated['bukti_pengeluaran'] = null;
        }

        // Handle upload new image
        if ($request->hasFile('bukti_pengeluaran')) {
            // Delete old image if exists
            if ($expense->bukti_pengeluaran && Storage::disk('public')->exists($expense->bukti_pengeluaran)) {
                Storage::disk('public')->delete($expense->bukti_pengeluaran);
            }
            $validated['bukti_pengeluaran'] = $request->file('bukti_pengeluaran')
                ->store('expenses', 'public');
        }

        $expense->update($validated);

    return redirect()->route('expenses.index')
        ->with('success', 'Pengeluaran berhasil diperbarui.');
}

public function destroy(string $id)
{
    if (Auth::user()->isOperator()) {
        abort(403, 'Anda tidak memiliki akses untuk menghapus data.');
    }

        $expense = Expense::findOrFail($id);

        // Delete image if exists
        if ($expense->bukti_pengeluaran && \Illuminate\Support\Facades\Storage::disk('public')->exists($expense->bukti_pengeluaran)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->bukti_pengeluaran);
        }

        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }

    private function parseDecimal($value)
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        return $value;
    }
}
