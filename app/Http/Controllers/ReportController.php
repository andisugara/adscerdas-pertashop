<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\DailyReport;
use App\Models\TankAddition;
use App\Models\Expense;
use App\Models\Deposit;
use App\Models\Salary;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Laporan dengan filter (Harian/Bulanan/Tahunan) - Dashboard lama
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'daily');
        $date = $request->get('date', now()->format('Y-m-d'));
        $month = $request->get('month', now()->format('Y-m'));
        $year = $request->get('year', now()->format('Y'));

        $setting = Setting::first();

        if (!$setting) {
            return redirect()->route('settings.index')
                ->with('error', 'Silakan atur konfigurasi pertashop terlebih dahulu');
        }

        $data = [
            'setting' => $setting,
            'filter' => $filter,
            'date' => $date,
            'month' => $month,
            'year' => $year,
        ];

        if ($filter === 'daily') {
            $data = array_merge($data, $this->getDailyReportData($date, $setting));
        } elseif ($filter === 'monthly') {
            $data = array_merge($data, $this->getMonthlyReport($month, $setting));
        } elseif ($filter === 'yearly') {
            $data = array_merge($data, $this->getYearlyReport($year, $setting));
        }

        return view('reports.index', $data);
    }

    // Laporan Harian - List per tanggal dengan perbandingan shift
    public function daily(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $setting = Setting::first();

        if (!$setting) {
            return redirect()->route('settings.index')
                ->with('error', 'Silakan atur konfigurasi pertashop terlebih dahulu');
        }

        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        // Group reports by date
        $reports = DailyReport::with(['user', 'shift'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal->format('Y-m-d');
            });

        $dailyData = [];
        foreach ($reports as $date => $dayReports) {
            $ta = $dayReports->first()->totalisator_awal;
            $tak = $dayReports->last()->totalisator_akhir;
            $salesLiter = $tak - $ta;
            $salesRp = $salesLiter * $setting->harga_jual;

            $sa = $dayReports->first()->stok_awal_mm;
            $sal = $sa * $setting->rumus;

            $sak = $dayReports->last()->stok_akhir_mm;
            $sakl = $sak * $setting->rumus;

            $tankAdditions = TankAddition::whereDate('tanggal', $date)->sum('jumlah_liter');

            $stokTersedia = ($sal + $tankAdditions) - $sakl;
            $ll = $salesLiter - $stokTersedia;
            $lr = $setting->hpp_per_liter * $ll;

            $stokAwalDO = $sal + $tankAdditions;
            $selisihStok = $sal - $sakl;

            $marginHari = (($setting->harga_jual - $setting->hpp_per_liter) * $salesLiter) + $lr;

            $dailyData[] = [
                'date' => $date,
                'ta' => $ta,
                'tak' => $tak,
                'salesLiter' => $salesLiter,
                'salesRp' => $salesRp,
                'sa' => $sa,
                'sal' => $sal,
                'sak' => $sak,
                'sakl' => $sakl,
                'll' => $ll,
                'lr' => $lr,
                'tankAdditions' => $tankAdditions,
                'stokAwalDO' => $stokAwalDO,
                'selisihStok' => $selisihStok,
                'marginHari' => $marginHari,
            ];
        }

        return view('reports.daily', [
            'setting' => $setting,
            'month' => $month,
            'dailyData' => $dailyData,
        ]);
    }

    // Laporan Bulanan
    public function monthly(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $setting = Setting::first();

        if (!$setting) {
            return redirect()->route('settings.index')
                ->with('error', 'Silakan atur konfigurasi pertashop terlebih dahulu');
        }

        $data = $this->getMonthlyReport($month, $setting);
        $data['month'] = $month;
        $data['setting'] = $setting;

        return view('reports.monthly', $data);
    }

    // Laporan Tahunan
    public function yearly(Request $request)
    {
        $year = $request->get('year', now()->format('Y'));
        $setting = Setting::first();

        if (!$setting) {
            return redirect()->route('settings.index')
                ->with('error', 'Silakan atur konfigurasi pertashop terlebih dahulu');
        }

        $data = $this->getYearlyReport($year, $setting);
        $data['year'] = $year;
        $data['setting'] = $setting;

        return view('reports.yearly', $data);
    }

    private function getDailyReportData($date, $setting)
    {
        $reports = DailyReport::with(['user', 'shift'])
            ->whereDate('tanggal', $date)
            ->orderBy('shift_id')
            ->get();

        $tankAdditions = TankAddition::whereDate('tanggal', $date)->sum('jumlah_liter');
        $expenses = Expense::whereDate('tanggal', $date)->sum('jumlah');
        $deposits = Deposit::whereDate('tanggal', $date)->get();

        $ta = $reports->first()->totalisator_awal ?? 0;
        $tak = $reports->last()->totalisator_akhir ?? 0;
        $salesLiter = $tak - $ta;
        $salesRp = $salesLiter * $setting->harga_jual;

        $sa = $reports->first()->stok_awal_mm ?? 0;
        $sal = $sa * $setting->rumus;

        $sak = $reports->last()->stok_akhir_mm ?? 0;
        $sakl = $sak * $setting->rumus;

        $stokTersedia = ($sal + $tankAdditions) - $sakl;
        $ll = $salesLiter - $stokTersedia;
        $lr = $setting->hpp_per_liter * $ll;
        $marginHari = (($setting->harga_jual - $setting->hpp_per_liter) * $salesLiter) + $lr;

        $totalDeposit = $deposits->sum('jumlah');
        $setorKeluar = $totalDeposit - $expenses;

        return [
            'reports' => $reports,
            'tankAdditions' => $tankAdditions,
            'totalExpenses' => $expenses,
            'deposits' => $deposits,
            'ta' => $ta,
            'tak' => $tak,
            'salesLiter' => $salesLiter,
            'salesRp' => $salesRp,
            'sa' => $sa,
            'sal' => $sal,
            'sak' => $sak,
            'sakl' => $sakl,
            'do' => $tankAdditions,
            'll' => $ll,
            'lr' => $lr,
            'marginHari' => $marginHari,
            'totalDeposit' => $totalDeposit,
            'setorKeluar' => $setorKeluar,
        ];
    }

    private function getMonthlyReport($month, $setting)
    {
        $startDate = Carbon::parse($month . '-01')->startOfMonth();
        $endDate = Carbon::parse($month . '-01')->endOfMonth();

        $reports = DailyReport::whereBetween('tanggal', [$startDate, $endDate])->get();
        $tankAdditions = TankAddition::whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah_liter');
        $expenses = Expense::whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');

        $ta = $reports->min('totalisator_awal') ?? 0;
        $tak = $reports->max('totalisator_akhir') ?? 0;
        $salesLiter = $tak - $ta;
        $salesRp = $salesLiter * $setting->harga_jual;

        $firstReport = $reports->sortBy('tanggal')->first();
        $lastReport = $reports->sortByDesc('tanggal')->last();

        $sa = $firstReport->stok_awal_mm ?? 0;
        $sal = $sa * $setting->rumus;

        $sak = $lastReport->stok_akhir_mm ?? 0;
        $sakl = $sak * $setting->rumus;

        $stokTersedia = ($sal + $tankAdditions) - $sakl;
        $ll = $salesLiter - $stokTersedia;
        $lr = $setting->hpp_per_liter * $ll;

        $hpp = $salesLiter * $setting->hpp_per_liter;
        $margin = $salesRp - $hpp + $lr;
        $operasional = $expenses;
        $zakat = ($margin - $operasional) * 0.025;

        // Get gaji for this month from salaries table
        $salaryRecord = Salary::where('bulan', $month)->first();
        $gaji = $salaryRecord ? $salaryRecord->jumlah : 0;

        $profit = ($margin - $operasional) - $zakat - $gaji;

        return [
            'ta' => $ta,
            'tak' => $tak,
            'salesLiter' => $salesLiter,
            'salesRp' => $salesRp,
            'sa' => $sa,
            'sal' => $sal,
            'sak' => $sak,
            'sakl' => $sakl,
            'do' => $tankAdditions,
            'll' => $ll,
            'lr' => $lr,
            'hpp' => $hpp,
            'margin' => $margin,
            'operasional' => $operasional,
            'zakat' => $zakat,
            'gaji' => $gaji,
            'profit' => $profit,
        ];
    }

    private function getYearlyReport($year, $setting)
    {
        $startDate = Carbon::parse($year . '-01-01')->startOfYear();
        $endDate = Carbon::parse($year . '-12-31')->endOfYear();

        $reports = DailyReport::whereBetween('tanggal', [$startDate, $endDate])->get();
        $tankAdditions = TankAddition::whereBetween('tanggal', [$startDate, $endDate])->get();
        $expenses = Expense::whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');

        $dailyReports = $reports->groupBy(function ($item) {
            return $item->tanggal->format('Y-m-d');
        });

        $totalSalesLiter = 0;
        $totalLosesLiter = 0;
        $pembelian = $tankAdditions->sum('jumlah_liter');

        foreach ($dailyReports as $date => $dayReports) {
            $ta = $dayReports->first()->totalisator_awal;
            $tak = $dayReports->last()->totalisator_akhir;
            $salesLiter = $tak - $ta;
            $totalSalesLiter += $salesLiter;

            $sa = $dayReports->first()->stok_awal_mm;
            $sal = $sa * $setting->rumus;

            $sak = $dayReports->last()->stok_akhir_mm;
            $sakl = $sak * $setting->rumus;

            $tankAdditionDay = TankAddition::whereDate('tanggal', $date)->sum('jumlah_liter');

            $stokTersedia = ($sal + $tankAdditionDay) - $sakl;
            $ll = $salesLiter - $stokTersedia;
            $totalLosesLiter += $ll;
        }

        $hppRp = $totalSalesLiter * $setting->hpp_per_liter;
        $losesRp = $totalLosesLiter * $setting->hpp_per_liter;
        $marginKotor = ($totalSalesLiter * $setting->harga_jual) - $hppRp + $losesRp;
        $zakat = $marginKotor * 0.025;

        // Get gaji for this year from salaries table
        $yearSalaries = Salary::where('bulan', 'like', $year . '-%')->sum('jumlah');
        $gaji = $yearSalaries;

        $profit = $marginKotor - $expenses - $zakat - $gaji;

        $penjualan = $totalSalesLiter;
        $selisihPenjualan = $pembelian - $penjualan;

        $stokAwal = $reports->first()->stok_awal_mm ?? 0;
        $stokAkhir = $reports->last()->stok_akhir_mm ?? 0;
        $selisihStok = $stokAkhir - $stokAwal;
        $loses = $selisihStok - $selisihPenjualan;

        $daysInMonth = $startDate->daysInMonth;
        $rataRataPenjualanPerHari = $totalSalesLiter / $daysInMonth;

        return [
            'hppPerLiter' => $setting->hpp_per_liter,
            'hppRp' => $hppRp,
            'sales' => $totalSalesLiter,
            'liter' => $totalSalesLiter,
            'losesLiter' => $totalLosesLiter,
            'losesRp' => $losesRp,
            'marginKotor' => $marginKotor,
            'operasional' => $expenses,
            'zakat' => $zakat,
            'gaji' => $gaji,
            'profit' => $profit,
            'pembelian' => $pembelian,
            'penjualan' => $penjualan,
            'selisihPenjualan' => $selisihPenjualan,
            'stokAwal' => $stokAwal,
            'stokAkhir' => $stokAkhir,
            'selisihStok' => $selisihStok,
            'sisaStokReal' => $selisihStok,
            'sisaStokBeli' => $selisihPenjualan,
            'loses' => $loses,
            'rataRataPenjualanPerHari' => $rataRataPenjualanPerHari,
        ];
    }
}
