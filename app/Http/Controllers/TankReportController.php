<?php

namespace App\Http\Controllers;

use App\Models\TankAddition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TankReportController extends Controller
{
    public function index(Request $request)
    {
        $query = TankAddition::with('user')
            ->whereNotNull('stok_awal')
            ->whereNotNull('stok_akhir');

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->where('tanggal', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        // Filter by month
        if ($request->has('month') && $request->month) {
            $query->whereMonth('tanggal', date('m', strtotime($request->month)))
                  ->whereYear('tanggal', date('Y', strtotime($request->month)));
        }

        $tankReports = $query->latest('tanggal')->paginate(15);

        // Calculate summary statistics
        $totalPengisian = $tankReports->sum('jumlah_liter');
        $totalLoses = $tankReports->sum(function($item) {
            return $item->loses;
        });
        $avgLoses = $tankReports->count() > 0 ? $totalLoses / $tankReports->count() : 0;

        // Analisa per No. Polisi
        $queryAll = TankAddition::whereNotNull('stok_awal')
            ->whereNotNull('stok_akhir')
            ->whereNotNull('no_polisi')
            ->where('no_polisi', '!=', '');

        // Apply same filters for analysis
        if ($request->has('start_date') && $request->start_date) {
            $queryAll->where('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $queryAll->where('tanggal', '<=', $request->end_date);
        }
        if ($request->has('month') && $request->month) {
            $queryAll->whereMonth('tanggal', date('m', strtotime($request->month)))
                     ->whereYear('tanggal', date('Y', strtotime($request->month)));
        }

        $analysisByNopol = $queryAll->get()->groupBy('no_polisi')->map(function($items, $nopol) {
            $count = $items->count();
            $totalLoses = $items->sum(function($item) {
                return $item->loses;
            });
            $avgLoses = $count > 0 ? $totalLoses / $count : 0;
            $totalPengisian = $items->sum('jumlah_liter');
            
            return [
                'no_polisi' => $nopol,
                'count' => $count,
                'total_pengisian' => $totalPengisian,
                'total_loses' => $totalLoses,
                'avg_loses' => $avgLoses,
                'status' => $avgLoses < -20 ? 'danger' : ($avgLoses < -10 ? 'warning' : 'success')
            ];
        })->sortByDesc('avg_loses')->take(10);

        // Analisa per Pengemudi
        $queryDriver = TankAddition::whereNotNull('stok_awal')
            ->whereNotNull('stok_akhir')
            ->whereNotNull('nama_pengemudi')
            ->where('nama_pengemudi', '!=', '');

        // Apply same filters
        if ($request->has('start_date') && $request->start_date) {
            $queryDriver->where('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $queryDriver->where('tanggal', '<=', $request->end_date);
        }
        if ($request->has('month') && $request->month) {
            $queryDriver->whereMonth('tanggal', date('m', strtotime($request->month)))
                        ->whereYear('tanggal', date('Y', strtotime($request->month)));
        }

        $analysisByDriver = $queryDriver->get()->groupBy('nama_pengemudi')->map(function($items, $driver) {
            $count = $items->count();
            $totalLoses = $items->sum(function($item) {
                return $item->loses;
            });
            $avgLoses = $count > 0 ? $totalLoses / $count : 0;
            $totalPengisian = $items->sum('jumlah_liter');
            
            return [
                'nama_pengemudi' => $driver,
                'count' => $count,
                'total_pengisian' => $totalPengisian,
                'total_loses' => $totalLoses,
                'avg_loses' => $avgLoses,
                'status' => $avgLoses < -20 ? 'danger' : ($avgLoses < -10 ? 'warning' : 'success')
            ];
        })->sortByDesc('avg_loses')->take(10);

        return view('tank-reports.index', compact(
            'tankReports', 
            'totalPengisian', 
            'totalLoses', 
            'avgLoses',
            'analysisByNopol',
            'analysisByDriver'
        ));
    }

    public function show(string $id)
    {
        $tankReport = TankAddition::with('user')->findOrFail($id);
        return view('tank-reports.show', compact('tankReport'));
    }
}
