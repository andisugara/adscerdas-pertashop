@extends('layout.app')

@section('title', 'My Subscription')

@section('content')
    <div class="row g-5 g-xl-10">
        <!-- Current Subscription Status -->
        <div class="col-xl-4">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Status Subscription</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    @if ($isInTrial && $trialSubscription)
                        <div class="d-flex align-items-center mb-7">
                            <span class="badge badge-light-primary fs-5">TRIAL</span>
                        </div>
                        <div class="mb-5">
                            <div class="text-gray-600 fw-semibold mb-2">Trial berakhir:</div>
                            <div class="fs-4 fw-bold text-gray-800">{{ $trialSubscription->ends_at->format('d M Y') }}
                            </div>
                            <div class="fs-7 text-gray-500">{{ $trialSubscription->ends_at->diffForHumans() }}</div>
                        </div>
                    @elseif($activeSubscription)
                        <div class="d-flex align-items-center mb-7">
                            <span class="badge badge-light-success fs-5">ACTIVE</span>
                        </div>
                        <div class="mb-5">
                            <div class="text-gray-600 fw-semibold mb-2">Paket:</div>
                            <div class="fs-4 fw-bold text-gray-800 text-uppercase">{{ $activeSubscription->plan_name }}
                            </div>
                        </div>
                        <div class="mb-5">
                            <div class="text-gray-600 fw-semibold mb-2">Berlaku hingga:</div>
                            <div class="fs-4 fw-bold text-gray-800">{{ $activeSubscription->ends_at->format('d M Y') }}
                            </div>
                            <div class="fs-7 text-gray-500">{{ $activeSubscription->ends_at->diffForHumans() }}</div>
                        </div>
                        <div class="mb-5">
                            <div class="text-gray-600 fw-semibold mb-2">Harga:</div>
                            <div class="fs-4 fw-bold text-gray-800">Rp
                                {{ number_format($activeSubscription->price, 0, ',', '.') }}</div>
                        </div>
                    @else
                        <div class="d-flex align-items-center mb-7">
                            <span class="badge badge-light-danger fs-5">EXPIRED</span>
                        </div>
                        <div class="text-gray-600 mb-5">
                            Subscription Anda telah berakhir. Silakan perpanjang untuk melanjutkan.
                        </div>
                    @endif

                    <a href="{{ route('subscription.plans') }}" class="btn btn-primary w-100">
                        <i class="ki-outline ki-plus fs-2"></i> Perpanjang / Upgrade
                    </a>
                </div>
            </div>
        </div>

        <!-- Subscription History -->
        <div class="col-xl-8">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Riwayat Subscription</span>
                        <span class="text-gray-500 mt-1 fw-semibold fs-6">{{ $subscriptions->count() }} transaksi</span>
                    </h3>
                </div>
                <div class="card-body pt-5">
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th class="min-w-120px">Paket</th>
                                    <th class="min-w-120px">Harga</th>
                                    <th class="min-w-120px">Periode</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-100px">Pembayaran</th>
                                    <th class="min-w-100px">Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subscriptions as $subscription)
                                    <tr>
                                        <td>
                                            <span
                                                class="text-gray-800 fw-bold text-uppercase">{{ $subscription->plan_name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-gray-800 fw-bold">Rp
                                                {{ number_format($subscription->price, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="text-gray-600">{{ $subscription->starts_at ? $subscription->starts_at->format('d M Y') : '-' }}</span>
                                            <br>
                                            <span
                                                class="text-gray-600">{{ $subscription->ends_at ? $subscription->ends_at->format('d M Y') : '-' }}</span>
                                        </td>
                                        <td>
                                            @if ($subscription->status === 'active')
                                                <span class="badge badge-light-success">Active</span>
                                            @elseif($subscription->status === 'pending')
                                                <div class="d-flex flex-column gap-2">
                                                    <span class="badge badge-light-warning">Pending</span>
                                                    @if ($subscription->payment_method === 'manual' && !$subscription->payment_proof)
                                                        <a href="{{ route('subscription.manual.payment', $subscription) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="ki-outline ki-cloud-upload fs-6"></i> Upload Bukti
                                                        </a>
                                                    @elseif($subscription->payment_method === 'manual' && $subscription->payment_proof)
                                                        <span class="text-muted fs-8">Menunggu approval</span>
                                                    @elseif($subscription->payment_method === 'duitku')
                                                        <a href="{{ route('subscription.duitku.payment', $subscription) }}"
                                                            class="btn btn-sm btn-success">
                                                            <i class="ki-outline ki-wallet fs-6"></i> Bayar Sekarang
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($subscription->status === 'expired')
                                                <span class="badge badge-light-danger">Expired</span>
                                            @else
                                                <span
                                                    class="badge badge-light-secondary">{{ $subscription->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($subscription->payment_status === 'paid')
                                                <span class="badge badge-light-success">Paid</span>
                                            @elseif($subscription->payment_status === 'pending')
                                                <span class="badge badge-light-warning">Pending</span>
                                            @else
                                                <span
                                                    class="badge badge-light-danger">{{ $subscription->payment_status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="text-gray-600">{{ $subscription->created_at->format('d M Y') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-gray-600 py-10">
                                            Belum ada riwayat subscription
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
