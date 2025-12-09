<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\DailyReport;
use App\Models\TankAddition;
use App\Models\Expense;
use App\Models\Deposit;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Jika operator, tampilkan halaman sederhana
        if (Auth::user()->isOperator()) {
            return view('dashboard.operator');
        }

        $setting = Setting::first();

        if (!$setting) {
            return redirect()->route('settings.index')
                ->with('error', 'Silakan atur konfigurasi pertashop terlebih dahulu');
        }

        // Get month from request or use current month
        $month = $request->get('month', now()->format('Y-m'));

        // Summary bulan yang dipilih
        $monthlyData = $this->getMonthlyReport($month, $setting);

        // Data 7 hari terakhir untuk grafik
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $reports = DailyReport::whereDate('tanggal', $date)->get();

            if ($reports->count() > 0) {
                $ta = $reports->first()->totalisator_awal;
                $tak = $reports->last()->totalisator_akhir;
                $sales = $tak - $ta;
            } else {
                $sales = 0;
            }

            $last7Days[] = [
                'date' => $date,
                'sales' => $sales,
            ];
        }

        return view('dashboard.index', [
            'setting' => $setting,
            'month' => $month,
            'monthlyData' => $monthlyData,
            'last7Days' => $last7Days,
        ]);
    }

    private function getDailyReport($date, $setting)
    {
        $reports = DailyReport::with(['user', 'shift'])
            ->whereDate('tanggal', $date)
            ->orderBy('shift_id')
            ->get();

        $tankAdditions = TankAddition::whereDate('tanggal', $date)->sum('jumlah_liter');
        $expenses = Expense::whereDate('tanggal', $date)->sum('jumlah');
        $deposits = Deposit::whereDate('tanggal', $date)->get();

        // Calculate daily totals
        $ta = $reports->first()->totalisator_awal ?? 0;
        $tak = $reports->last()->totalisator_akhir ?? 0;
        $salesLiter = $tak - $ta; // Totalisator selalu naik, jadi TAK - TA
        $salesRp = $salesLiter * $setting->harga_jual;

        $sa = $reports->first()->stok_awal_mm ?? 0;
        $sal = $sa * $setting->rumus;

        $sak = $reports->last()->stok_akhir_mm ?? 0;
        $sakl = $sak * $setting->rumus;

        // Loses (Liter) = Sales - ((SAL + DO) - SAKL)
        $stokTersedia = ($sal + $tankAdditions) - $sakl; // Stok yang seharusnya terpakai
        $ll = $salesLiter - $stokTersedia; // Selisih antara sales aktual vs stok terpakai
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
        $tankAdditions = TankAddition::whereBetween('tanggal', [$startDate, $endDate])->get();
        $expenses = Expense::whereBetween('tanggal', [$startDate, $endDate])->sum('jumlah');

        // Group reports by date for daily calculations
        $dailyReports = $reports->groupBy(function ($item) {
            return $item->tanggal->format('Y-m-d');
        });

        $totalSalesLiter = 0;
        $totalLosesLiter = 0;
        $pembelian = $tankAdditions->sum('jumlah_liter');

        foreach ($dailyReports as $date => $dayReports) {
            $ta = $dayReports->first()->totalisator_awal;
            $tak = $dayReports->last()->totalisator_akhir;
            $salesLiter = $tak - $ta; // Totalisator selalu naik, jadi TAK - TA
            $totalSalesLiter += $salesLiter;

            $sa = $dayReports->first()->stok_awal_mm;
            $sal = $sa * $setting->rumus;

            $sak = $dayReports->last()->stok_akhir_mm;
            $sakl = $sak * $setting->rumus;

            $tankAdditionDay = TankAddition::whereDate('tanggal', $date)->sum('jumlah_liter');

            // Loses (Liter) = Sales - ((SAL + DO) - SAKL)
            $stokTersedia = ($sal + $tankAdditionDay) - $sakl;
            $ll = $salesLiter - $stokTersedia;
            $totalLosesLiter += $ll;
        }

        $hppRp = $totalSalesLiter * $setting->hpp_per_liter;
        $losesRp = $totalLosesLiter * $setting->hpp_per_liter;
        $marginKotor = ($totalSalesLiter * $setting->harga_jual) - $hppRp + $losesRp;
        $zakat = $marginKotor * 0.025;

        // Get gaji for this month from salaries table
        $salaryRecord = Salary::where('bulan', $month)->first();
        $gaji = $salaryRecord ? $salaryRecord->jumlah : 0;

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
            $salesLiter = $tak - $ta; // Totalisator selalu naik, jadi TAK - TA
            $totalSalesLiter += $salesLiter;

            $sa = $dayReports->first()->stok_awal_mm;
            $sal = $sa * $setting->rumus;

            $sak = $dayReports->last()->stok_akhir_mm;
            $sakl = $sak * $setting->rumus;

            $tankAdditionDay = TankAddition::whereDate('tanggal', $date)->sum('jumlah_liter');

            // Loses (Liter) = Sales - ((SAL + DO) - SAKL)
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

        $daysInYear = 365;
        $rataRataPenjualanPerHari = $totalSalesLiter / $daysInYear;

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
