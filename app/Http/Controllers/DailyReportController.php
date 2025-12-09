<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\Shift;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyReport::with(['user', 'shift']);

        if ($request->has('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if (Auth::user()->isOperator()) {
            $query->where('user_id', Auth::id());
        }

        $reports = $query->latest('tanggal')->paginate(15);

        return view('daily-reports.index', compact('reports'));
    }

    public function create()
    {
        $shifts = Shift::where('aktif', true)->orderBy('urutan')->get();
        $setting = Setting::first();

        return view('daily-reports.create', compact('shifts', 'setting'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'totalisator_awal' => 'required|string',
            'totalisator_akhir' => 'required|string',
            'stok_awal_mm' => 'required|string',
            'stok_akhir_mm' => 'required|string',
        ]);

        // Convert format Indonesia (1.234,56) ke format database (1234.56)
        $validated['totalisator_awal'] = $this->parseDecimal($validated['totalisator_awal']);
        $validated['totalisator_akhir'] = $this->parseDecimal($validated['totalisator_akhir']);
        $validated['stok_awal_mm'] = $this->parseDecimal($validated['stok_awal_mm']);
        $validated['stok_akhir_mm'] = $this->parseDecimal($validated['stok_akhir_mm']);

        $validated['user_id'] = Auth::id();

        $exists = DailyReport::where('user_id', $validated['user_id'])
            ->where('shift_id', $validated['shift_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['tanggal' => 'Laporan untuk shift ini pada tanggal tersebut sudah ada.'])->withInput();
        }

        // Validasi: Totalisator Awal harus sama dengan Totalisator Akhir shift sebelumnya
        $previousReport = DailyReport::where('user_id', $validated['user_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('shift_id', '<', $validated['shift_id'])
            ->orderBy('shift_id', 'desc')
            ->first();

        if ($previousReport) {
            // Bandingkan dengan toleransi 0.001 untuk menghindari floating point issue
            if (abs($previousReport->totalisator_akhir - $validated['totalisator_awal']) > 0.001) {
                return back()->withErrors([
                    'totalisator_awal' => 'Totalisator Awal harus sama dengan Totalisator Akhir shift sebelumnya (' . number_format($previousReport->totalisator_akhir, 3, ',', '.') . ')'
                ])->withInput();
            }
        }

        DailyReport::create($validated);

        return redirect()->route('daily-reports.index')
            ->with('success', 'Laporan harian berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $dailyReport = DailyReport::with(['user', 'shift'])->findOrFail($id);
        $setting = Setting::first();

        return view('daily-reports.show', compact('dailyReport', 'setting'));
    }

    public function edit(string $id)
    {
        $dailyReport = DailyReport::findOrFail($id);

        if (Auth::user()->isOperator() && $dailyReport->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        $shifts = Shift::where('aktif', true)->orderBy('urutan')->get();
        $setting = Setting::first();

        return view('daily-reports.edit', compact('dailyReport', 'shifts', 'setting'));
    }

    public function update(Request $request, string $id)
    {
        $dailyReport = DailyReport::findOrFail($id);

        if (Auth::user()->isOperator() && $dailyReport->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit laporan ini.');
        }

        $validated = $request->validate([
            'shift_id' => 'required|exists:shifts,id',
            'tanggal' => 'required|date',
            'totalisator_awal' => 'required|string',
            'totalisator_akhir' => 'required|string',
            'stok_awal_mm' => 'required|string',
            'stok_akhir_mm' => 'required|string',
        ]);

        // Convert format Indonesia (1.234,56) ke format database (1234.56)
        $validated['totalisator_awal'] = $this->parseDecimal($validated['totalisator_awal']);
        $validated['totalisator_akhir'] = $this->parseDecimal($validated['totalisator_akhir']);
        $validated['stok_awal_mm'] = $this->parseDecimal($validated['stok_awal_mm']);
        $validated['stok_akhir_mm'] = $this->parseDecimal($validated['stok_akhir_mm']);

        // Validasi: Totalisator Awal harus sama dengan Totalisator Akhir shift sebelumnya
        $previousReport = DailyReport::where('user_id', $dailyReport->user_id)
            ->whereDate('tanggal', $validated['tanggal'])
            ->where('shift_id', '<', $validated['shift_id'])
            ->where('id', '!=', $dailyReport->id)
            ->orderBy('shift_id', 'desc')
            ->first();

        if ($previousReport) {
            if (abs($previousReport->totalisator_akhir - $validated['totalisator_awal']) > 0.001) {
                return back()->withErrors([
                    'totalisator_awal' => 'Totalisator Awal harus sama dengan Totalisator Akhir shift sebelumnya (' . number_format($previousReport->totalisator_akhir, 3, ',', '.') . ')'
                ])->withInput();
            }
        }

        $dailyReport->update($validated);

        return redirect()->route('daily-reports.index')
            ->with('success', 'Laporan harian berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        if (Auth::user()->isOperator()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus laporan.');
        }

        $dailyReport = DailyReport::findOrFail($id);
        $dailyReport->delete();

        return redirect()->route('daily-reports.index')
            ->with('success', 'Laporan harian berhasil dihapus.');
    }

    /**
     * Parse format Indonesia (1.234,56) ke format database (1234.56)
     */
    private function parseDecimal($value)
    {
        // Trim whitespace
        $value = trim($value);

        // Hapus separator ribuan (titik)
        $value = str_replace('.', '', $value);

        // Ganti koma desimal dengan titik
        $value = str_replace(',', '.', $value);

        return $value;
    }
}
