<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);

        $validated['jumlah'] = $this->parseDecimal($validated['jumlah']);
        $validated['user_id'] = Auth::id();

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
        ]);

        $expense = Expense::findOrFail($id);
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
