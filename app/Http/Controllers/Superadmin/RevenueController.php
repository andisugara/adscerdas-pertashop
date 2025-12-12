<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');

        // Get paid subscriptions
        $query = Subscription::where('payment_status', 'paid')
            ->whereNotNull('approved_at')
            ->with('organization');

        // Filter by year
        if ($year) {
            $query->whereYear('approved_at', $year);
        }

        // Filter by month if specified
        if ($month) {
            $query->whereMonth('approved_at', $month);
        }

        $subscriptions = $query->orderBy('approved_at', 'desc')->paginate(25);

        // Calculate stats
        $stats = [
            'total_revenue' => Subscription::where('payment_status', 'paid')->sum('price'),
            'yearly_revenue' => Subscription::where('payment_status', 'paid')
                ->whereYear('approved_at', $year)
                ->sum('price'),
            'monthly_revenue' => $month
                ? Subscription::where('payment_status', 'paid')
                ->whereYear('approved_at', $year)
                ->whereMonth('approved_at', $month)
                ->sum('price')
                : null,
            'total_paid_subscriptions' => Subscription::where('payment_status', 'paid')->count(),
        ];

        // Monthly revenue breakdown for the year
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenue = Subscription::where('payment_status', 'paid')
                ->whereYear('approved_at', $year)
                ->whereMonth('approved_at', $m)
                ->sum('price');

            $monthlyData[] = [
                'month' => $m,
                'month_name' => Carbon::create()->month($m)->format('F'),
                'revenue' => $revenue,
            ];
        }

        // Get available years
        $availableYears = Subscription::where('payment_status', 'paid')
            ->whereNotNull('approved_at')
            ->selectRaw('DISTINCT YEAR(approved_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([now()->year]);
        }

        return view('superadmin.revenue.index', compact(
            'subscriptions',
            'stats',
            'monthlyData',
            'year',
            'month',
            'availableYears'
        ));
    }
}
