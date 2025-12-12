@extends('layout.app')

@section('title', 'Kelola Pertashop')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pertashop Saya</h3>
            <div class="card-toolbar">
                <a href="{{ route('organizations.create') }}" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah Pertashop
                </a>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">Nama Pertashop</th>
                            <th class="min-w-120px">Kode</th>
                            <th class="min-w-150px">Alamat</th>
                            <th class="min-w-100px">Subscription</th>
                            <th class="min-w-100px text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizations as $organization)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-start flex-column">
                                            <span
                                                class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ $organization->name }}</span>
                                            @if (auth()->user()->active_organization_id == $organization->id)
                                                <span class="badge badge-light-primary">Aktif Sekarang</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-gray-800 fw-bold">{{ $organization->kode_pertashop ?? '-' }}</span>
                                </td>
                                <td>
                                    <span class="text-gray-600">{{ $organization->address ?: '-' }}</span>
                                </td>
                                <td>
                                    @if ($organization->isInTrial())
                                        <span class="badge badge-light-primary">
                                            <i class="ki-outline ki-gift fs-6 me-1"></i>
                                            Trial
                                        </span>
                                    @elseif($organization->hasActiveSubscription())
                                        @php
                                            $activeSub = $organization->activeSubscription;
                                        @endphp
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge badge-light-success">
                                                <i class="ki-outline ki-check-circle fs-6 me-1"></i>
                                                {{ ucfirst($activeSub->plan_name ?? 'Active') }}
                                            </span>
                                            @if ($activeSub)
                                                <span class="text-muted fs-8">s/d
                                                    {{ $activeSub->ends_at->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge badge-light-danger">
                                                <i class="ki-outline ki-cross-circle fs-6 me-1"></i>
                                                No Subscription
                                            </span>
                                            <a href="{{ route('subscription.plans') }}" class="btn btn-sm btn-primary mt-1">
                                                Subscribe
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        @if (auth()->user()->active_organization_id != $organization->id)
                                            <form action="{{ route('organizations.switch', $organization) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light-primary"
                                                    title="Switch ke pertashop ini">
                                                    <i class="ki-outline ki-arrows-circle fs-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <a href="{{ route('organizations.edit', $organization) }}"
                                            class="btn btn-sm btn-light-warning" title="Edit">
                                            <i class="ki-outline ki-pencil fs-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-600 py-10">
                                    Belum ada pertashop. <a href="{{ route('organizations.create') }}"
                                        class="fw-bold">Tambah pertashop pertama Anda</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
