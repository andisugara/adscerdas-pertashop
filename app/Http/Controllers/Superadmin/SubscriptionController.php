<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with('organization')
            ->latest()
            ->paginate(20);

        return view('superadmin.subscriptions.index', compact('subscriptions'));
    }

    public function show(Subscription $subscription)
    {
        $subscription->load('organization', 'approver');
        return view('superadmin.subscriptions.show', compact('subscription'));
    }

    public function approve(Subscription $subscription)
    {
        if ($subscription->status === 'active') {
            return back()->withErrors(['error' => 'Subscription sudah aktif']);
        }

        $subscription->update([
            'status' => 'active',
            'payment_status' => 'paid',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // Activate organization if not active
        if (!$subscription->organization->is_active) {
            $subscription->organization->update(['is_active' => true]);
        }

        return back()->with('success', 'Subscription berhasil disetujui dan organisasi diaktifkan');
    }

    public function reject(Subscription $subscription)
    {
        if ($subscription->status === 'active') {
            return back()->withErrors(['error' => 'Tidak bisa reject subscription yang sudah aktif']);
        }

        $subscription->update([
            'status' => 'rejected',
            'payment_status' => 'failed',
            'notes' => 'Ditolak oleh superadmin',
        ]);

        return back()->with('success', 'Subscription berhasil ditolak');
    }
}
