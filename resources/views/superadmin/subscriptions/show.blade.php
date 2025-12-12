@extends('superadmin.layout')

@section('title', 'Detail Subscription')

@section('content')
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

    @if ($errors->any())
        <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
            <i class="ki-duotone ki-information fs-2hx text-danger me-4">
                <span class="path1"></span>
                <span class="path2"></span>
                <span class="path3"></span>
            </i>
            <div class="d-flex flex-column">
                @foreach ($errors->all() as $error)
                    <span>{{ $error }}</span>
                @endforeach
            </div>
        </div>
    @endif

    <div class="row g-5">
        <!-- Subscription Details -->
        <div class="col-lg-8">
            <div class="card mb-5">
                <div class="card-header">
                    <h3 class="card-title">Detail Subscription</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">ID Subscription</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">#{{ $subscription->id }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Organisasi</label>
                        <div class="col-lg-8">
                            <a href="{{ route('superadmin.organizations.show', $subscription->organization) }}"
                                class="fw-bold fs-6 text-primary text-hover-dark">
                                {{ $subscription->organization->name }}
                            </a>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Paket</label>
                        <div class="col-lg-8">
                            <span
                                class="badge badge-light-{{ $subscription->plan_name == 'yearly' ? 'success' : 'primary' }} fs-6">
                                {{ ucfirst($subscription->plan_name) }}
                            </span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Harga</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">Rp
                                {{ number_format($subscription->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Periode</label>
                        <div class="col-lg-8">
                            <span class="fw-bold fs-6 text-gray-800">
                                {{ $subscription->starts_at->format('d M Y') }} -
                                {{ $subscription->ends_at->format('d M Y') }}
                            </span>
                            <div class="text-muted fs-7 mt-1">
                                ({{ $subscription->starts_at->diffInDays($subscription->ends_at) }} hari)
                            </div>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Metode Pembayaran</label>
                        <div class="col-lg-8">
                            <span class="badge badge-light fs-6">{{ ucfirst($subscription->payment_method) }}</span>
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Status</label>
                        <div class="col-lg-8">
                            @if ($subscription->status === 'active')
                                <span class="badge badge-light-success fs-6">Active</span>
                            @elseif($subscription->status === 'pending')
                                <span class="badge badge-light-warning fs-6">Pending</span>
                            @elseif($subscription->status === 'rejected')
                                <span class="badge badge-light-danger fs-6">Rejected</span>
                            @else
                                <span class="badge badge-light-secondary fs-6">{{ ucfirst($subscription->status) }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-7">
                        <label class="col-lg-4 fw-bold text-muted">Status Pembayaran</label>
                        <div class="col-lg-8">
                            @if ($subscription->payment_status === 'paid')
                                <span class="badge badge-light-success fs-6">Paid</span>
                            @elseif($subscription->payment_status === 'pending')
                                <span class="badge badge-light-warning fs-6">Pending</span>
                            @else
                                <span
                                    class="badge badge-light-danger fs-6">{{ ucfirst($subscription->payment_status) }}</span>
                            @endif
                        </div>
                    </div>

                    @if ($subscription->payment_proof)
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Bukti Pembayaran</label>
                            <div class="col-lg-8">
                                <a href="{{ Storage::url($subscription->payment_proof) }}" target="_blank"
                                    class="btn btn-sm btn-light-primary">
                                    <i class="ki-outline ki-file fs-4 me-1"></i>
                                    Lihat Bukti Pembayaran
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($subscription->merchant_order_id)
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Merchant Order ID</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $subscription->merchant_order_id }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($subscription->duitku_reference)
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Duitku Reference</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-800">{{ $subscription->duitku_reference }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($subscription->notes)
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-bold text-muted">Catatan</label>
                            <div class="col-lg-8">
                                <span class="fw-semibold fs-6 text-gray-600">{{ $subscription->notes }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions & Timeline -->
        <div class="col-lg-4">
            <!-- Actions Card -->
            @if ($subscription->status === 'pending')
                <div class="card mb-5">
                    <div class="card-header">
                        <h3 class="card-title">Tindakan</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.subscriptions.approve', $subscription) }}" method="POST"
                            class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success w-100"
                                onclick="return confirm('Setujui subscription ini?')">
                                <i class="ki-outline ki-check fs-4 me-1"></i>
                                Setujui Subscription
                            </button>
                        </form>

                        <form action="{{ route('superadmin.subscriptions.reject', $subscription) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100"
                                onclick="return confirm('Tolak subscription ini?')">
                                <i class="ki-outline ki-cross fs-4 me-1"></i>
                                Tolak Subscription
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Timeline Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Timeline</h3>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Created -->
                        <div class="timeline-item">
                            <div class="timeline-line w-40px"></div>
                            <div class="timeline-icon symbol symbol-circle symbol-40px">
                                <div class="symbol-label bg-light-primary">
                                    <i class="ki-duotone ki-plus fs-2 text-primary">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </div>
                            </div>
                            <div class="timeline-content mb-10 mt-n1">
                                <div class="pe-3 mb-5">
                                    <div class="fs-5 fw-bold mb-2">Subscription Dibuat</div>
                                    <div class="d-flex align-items-center mt-1 fs-6">
                                        <div class="text-muted me-2 fs-7">
                                            {{ $subscription->created_at->format('d M Y, H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($subscription->approved_at)
                            <!-- Approved -->
                            <div class="timeline-item">
                                <div class="timeline-line w-40px"></div>
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light-success">
                                        <i class="ki-duotone ki-check fs-2 text-success">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <div class="timeline-content mb-10 mt-n1">
                                    <div class="pe-3 mb-5">
                                        <div class="fs-5 fw-bold mb-2">Subscription Disetujui</div>
                                        <div class="d-flex align-items-center mt-1 fs-6">
                                            <div class="text-muted me-2 fs-7">
                                                {{ $subscription->approved_at->format('d M Y, H:i') }}</div>
                                        </div>
                                        @if ($subscription->approver)
                                            <div class="text-muted fs-7 mt-1">Oleh: {{ $subscription->approver->name }}
                                            </div>
                                        @else
                                            <div class="text-muted fs-7 mt-1">Auto-approved (Duitku)</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($subscription->status === 'rejected')
                            <!-- Rejected -->
                            <div class="timeline-item">
                                <div class="timeline-line w-40px"></div>
                                <div class="timeline-icon symbol symbol-circle symbol-40px">
                                    <div class="symbol-label bg-light-danger">
                                        <i class="ki-duotone ki-cross fs-2 text-danger">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                    </div>
                                </div>
                                <div class="timeline-content mb-10 mt-n1">
                                    <div class="pe-3 mb-5">
                                        <div class="fs-5 fw-bold mb-2">Subscription Ditolak</div>
                                        <div class="d-flex align-items-center mt-1 fs-6">
                                            <div class="text-muted me-2 fs-7">
                                                {{ $subscription->updated_at->format('d M Y, H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
