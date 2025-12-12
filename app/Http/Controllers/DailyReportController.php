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

        $reports = $query->latest('tanggal')->latest('shift_id')->paginate(15);

        return view('daily-reports.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $organization = Auth::user()->activeOrganization;
        $shifts = Shift::where('aktif', true)->orderBy('urutan')->get();
        $setting = Setting::first();

        // Get default values from previous report or organization initial data
        $defaultTotalisatorAwal = null;
        $defaultStokAwal = null;

        // Jika ada shift_id dan tanggal dari request (saat user pilih shift)
        if ($request->has('shift_id') && $request->has('tanggal')) {
            $shiftId = $request->shift_id;
            $tanggal = $request->tanggal;

            // Cari laporan shift sebelumnya di tanggal yang sama
            $previousReport = DailyReport::whereDate('tanggal', $tanggal)
                ->where('shift_id', '<', $shiftId)
                ->orderBy('shift_id', 'desc')
                ->first();

            if ($previousReport) {
                $defaultTotalisatorAwal = $previousReport->totalisator_akhir;
                $defaultStokAwal = $previousReport->stok_akhir_mm;
            } else {
                // Jika tidak ada laporan sebelumnya di hari yang sama, gunakan data awal organization
                $defaultTotalisatorAwal = $organization->totalisator_awal ?? 0;
                $defaultStokAwal = $organization->stok_awal ?? 0;
            }
        } else {
            // Jika tidak ada request, ambil dari laporan terakhir secara keseluruhan
            $lastReport = DailyReport::orderBy('tanggal', 'desc')
                ->orderBy('shift_id', 'desc')
                ->first();

            if ($lastReport) {
                $defaultTotalisatorAwal = $lastReport->totalisator_akhir;
                $defaultStokAwal = $lastReport->stok_akhir_mm;
            } else {
                // Jika belum ada laporan sama sekali, gunakan data awal organization
                $defaultTotalisatorAwal = $organization->totalisator_awal ?? 0;
                $defaultStokAwal = $organization->stok_awal ?? 0;
            }
        }

        return view('daily-reports.create', compact('shifts', 'setting', 'defaultTotalisatorAwal', 'defaultStokAwal'));
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

        // Validasi: Cek duplikat berdasarkan shift dan tanggal saja (tanpa user_id)
        $exists = DailyReport::where('shift_id', $validated['shift_id'])
            ->whereDate('tanggal', $validated['tanggal'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['tanggal' => 'Laporan untuk shift ini pada tanggal tersebut sudah ada.'])->withInput();
        }

        // Validasi: Totalisator Awal harus sama dengan Totalisator Akhir shift sebelumnya
        $previousReport = DailyReport::whereDate('tanggal', $validated['tanggal'])
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
        $shifts = Shift::where('aktif', true)->orderBy('urutan')->get();
        $setting = Setting::first();

        return view('daily-reports.edit', compact('dailyReport', 'shifts', 'setting'));
    }

    public function update(Request $request, string $id)
    {
        $dailyReport = DailyReport::findOrFail($id);

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
        $previousReport = DailyReport::whereDate('tanggal', $validated['tanggal'])
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
