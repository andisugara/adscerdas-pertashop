@extends('superadmin.layout')

@section('title', 'Dashboard')

@section('content')
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
            <div class="d-flex flex-column">
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Total Organizations -->
        <div class="col-xl-3">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Total Organisasi</span>
                    </h3>
                    <div class="card-toolbar">
                        <i class="ki-duotone ki-home-2 fs-3x text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="card-body pt-5">
                    <div class="d-flex align-items-center mb-7">
                        <span class="fs-3hx fw-bold text-gray-800 me-2 lh-1">{{ $stats['total_organizations'] }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-semibold fs-6 text-gray-400 me-2">Active:</span>
                        <span class="fw-bold fs-6 text-success">{{ $stats['active_organizations'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Subscriptions -->
        <div class="col-xl-3">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Total Subscription</span>
                    </h3>
                    <div class="card-toolbar">
                        <i class="ki-duotone ki-price-tag fs-3x text-success">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </div>
                </div>
                <div class="card-body pt-5">
                    <div class="d-flex align-items-center mb-7">
                        <span class="fs-3hx fw-bold text-gray-800 me-2 lh-1">{{ $stats['total_subscriptions'] }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="fw-semibold fs-6 text-gray-400 me-2">Active:</span>
                            <span class="fw-bold fs-6 text-success">{{ $stats['active_subscriptions'] }}</span>
                        </div>
                        <div>
                            <span class="fw-semibold fs-6 text-gray-400 me-2">Pending:</span>
                            <span class="fw-bold fs-6 text-warning">{{ $stats['pending_subscriptions'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="col-xl-3">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Total Pengguna</span>
                    </h3>
                    <div class="card-toolbar">
                        <i class="ki-duotone ki-people fs-3x text-info">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                            <span class="path5"></span>
                        </i>
                    </div>
                </div>
                <div class="card-body pt-5">
                    <div class="d-flex align-items-center mb-7">
                        <span class="fs-3hx fw-bold text-gray-800 me-2 lh-1">{{ $stats['total_users'] }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-semibold fs-6 text-gray-400">Tidak termasuk Superadmin</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="col-xl-3">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Total Pendapatan</span>
                    </h3>
                    <div class="card-toolbar">
                        <i class="ki-duotone ki-wallet fs-3x text-warning">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </div>
                </div>
                <div class="card-body pt-5">
                    <div class="d-flex align-items-center mb-7">
                        <span class="fs-2qx fw-bold text-gray-800 me-2 lh-1">Rp
                            {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="fw-semibold fs-6 text-gray-400 me-2">Bulan ini:</span>
                        <span class="fw-bold fs-6 text-success">Rp
                            {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Subscriptions -->
    @if ($pendingSubscriptions->count() > 0)
        <div class="card mb-5 mb-xl-8">
            <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold fs-3 mb-1">Subscription Pending</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">{{ $pendingSubscriptions->count() }} menunggu
                        persetujuan</span>
                </h3>
                <div class="card-toolbar">
                    <a href="{{ route('superadmin.subscriptions.index') }}" class="btn btn-sm btn-light-primary">
                        <i class="ki-outline ki-eye fs-4 me-1"></i>
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body py-3">
                <div class="table-responsive">
                    <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                        <thead>
                            <tr class="fw-bold text-muted">
                                <th class="min-w-150px">Organisasi</th>
                                <th class="min-w-100px">Paket</th>
                                <th class="min-w-120px">Harga</th>
                                <th class="min-w-120px">Metode Bayar</th>
                                <th class="min-w-100px">Waktu</th>
                                <th class="min-w-100px text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingSubscriptions as $sub)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="symbol symbol-45px me-5">
                                                <span class="symbol-label bg-light-primary text-primary fw-bold">
                                                    {{ substr($sub->organization->name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-start flex-column">
                                                <span
                                                    class="text-dark fw-bold mb-1 fs-6">{{ $sub->organization->name }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-light-{{ $sub->plan_name == 'yearly' ? 'success' : 'primary' }}">
                                            {{ ucfirst($sub->plan_name) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-dark fw-bold d-block fs-6">Rp
                                            {{ number_format($sub->price, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ ucfirst($sub->payment_method) }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted fw-semibold d-block">{{ $sub->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('superadmin.subscriptions.show', $sub) }}"
                                            class="btn btn-icon btn-light-primary btn-sm">
                                            <i class="ki-outline ki-eye fs-4"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Organizations -->
    <div class="card">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Organisasi Terbaru</span>
                <span class="text-muted mt-1 fw-semibold fs-7">{{ $recentOrganizations->count() }} organisasi
                    terbaru</span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('superadmin.organizations.index') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-outline ki-eye fs-4 me-1"></i>
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Nama</th>
                            <th class="min-w-150px">Email</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-120px">Terdaftar</th>
                            <th class="min-w-100px text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrganizations as $org)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-5">
                                            <span class="symbol-label bg-light-success text-success fw-bold">
                                                {{ substr($org->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bold mb-1 fs-6">{{ $org->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold d-block">{{ $org->email ?? '-' }}</span>
                                </td>
                                <td>
                                    @if ($org->is_active)
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="text-muted fw-semibold d-block">{{ $org->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('superadmin.organizations.show', $org) }}"
                                        class="btn btn-icon btn-light-primary btn-sm">
                                        <i class="ki-outline ki-eye fs-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
