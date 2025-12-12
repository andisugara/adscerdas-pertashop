<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_organizations' => Organization::count(),
            'active_organizations' => Organization::where('is_active', true)->count(),
            'total_subscriptions' => Subscription::count(),
            'pending_subscriptions' => Subscription::where('status', 'pending')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_users' => User::where('role', '!=', 'superadmin')->count(),
            'total_revenue' => Subscription::where('payment_status', 'paid')->sum('price'),
            'monthly_revenue' => Subscription::where('payment_status', 'paid')
                ->whereYear('approved_at', now()->year)
                ->whereMonth('approved_at', now()->month)
                ->sum('price'),
        ];

        $recentOrganizations = Organization::latest()->take(5)->get();
        $pendingSubscriptions = Subscription::where('status', 'pending')
            ->with('organization')
            ->latest()
            ->take(10)
            ->get();

        return view('superadmin.dashboard', compact('stats', 'recentOrganizations', 'pendingSubscriptions'));
    }
}
