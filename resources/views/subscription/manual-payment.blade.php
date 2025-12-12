@extends('layout.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
    <div class="row g-5 g-xl-10">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Upload Bukti Pembayaran</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-sm btn-light">
                            <i class="ki-outline ki-arrow-left fs-4"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-7">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-7">
                        <!-- Subscription Details -->
                        <div class="col-lg-6">
                            <div class="card border border-gray-300">
                                <div class="card-header bg-light">
                                    <h4 class="card-title mb-0">Detail Subscription</h4>
                                </div>
                                <div class="card-body">
                                    <div class="mb-5">
                                        <label class="form-label text-gray-600">Paket</label>
                                        <div class="fs-3 fw-bold text-gray-800 text-uppercase">
                                            {{ $subscription->plan_name }}</div>
                                    </div>

                                    <div class="separator my-5"></div>

                                    <div class="mb-5">
                                        <label class="form-label text-gray-600">Total Pembayaran</label>
                                        <div class="fs-2hx fw-bold text-primary">Rp
                                            {{ number_format($subscription->price, 0, ',', '.') }}</div>
                                    </div>

                                    <div class="separator my-5"></div>

                                    <div class="mb-5">
                                        <label class="form-label text-gray-600">Periode</label>
                                        <div class="fs-6 text-gray-800">
                                            {{ $subscription->starts_at->format('d M Y') }} -
                                            {{ $subscription->ends_at->format('d M Y') }}
                                        </div>
                                        <div class="text-muted fs-7">
                                            {{ $subscription->starts_at->diffInDays($subscription->ends_at) }} hari
                                        </div>
                                    </div>

                                    <div class="separator my-5"></div>

                                    <div class="mb-0">
                                        <label class="form-label text-gray-600">Status</label>
                                        <div>
                                            <span class="badge badge-light-warning fs-6">Menunggu Pembayaran</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transfer Instructions & Upload Form -->
                        <div class="col-lg-6">
                            <!-- Transfer Instructions -->
                            <div class="card border border-primary mb-5">
                                <div class="card-header bg-light-primary">
                                    <h4 class="card-title mb-0">
                                        <i class="ki-outline ki-information-5 fs-2 text-primary me-2"></i>
                                        Informasi Transfer
                                    </h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-gray-700 mb-5">Silakan transfer ke rekening berikut:</p>

                                    <div class="bg-light rounded p-5 mb-5">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="symbol symbol-50px me-3">
                                                <div class="symbol-label bg-primary">
                                                    <i class="ki-outline ki-bank fs-2x text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="fs-4 fw-bold text-gray-800">
                                                    {{ \App\Models\SystemSetting::get('bank_name', 'Bank BCA') }}</div>
                                                <div class="text-muted fs-7">Transfer Manual</div>
                                            </div>
                                        </div>

                                        <div class="separator my-4"></div>

                                        <div class="mb-3">
                                            <div class="text-gray-600 fs-7 mb-1">Nomor Rekening</div>
                                            <div class="fs-3 fw-bold text-gray-800">
                                                {{ \App\Models\SystemSetting::get('bank_account_number', '1234567890') }}
                                            </div>
                                        </div>

                                        <div>
                                            <div class="text-gray-600 fs-7 mb-1">Atas Nama</div>
                                            <div class="fs-6 fw-bold text-gray-800">
                                                {{ \App\Models\SystemSetting::get('bank_account_name', 'PT Pertashop Indonesia') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning d-flex align-items-center p-4">
                                        <i class="ki-outline ki-information fs-2 text-warning me-3"></i>
                                        <div class="fs-7">
                                            Transfer sesuai <strong>nominal yang tertera</strong> agar proses verifikasi
                                            lebih cepat.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Form -->
                            <div class="card border border-gray-300">
                                <div class="card-header bg-light">
                                    <h4 class="card-title mb-0">
                                        <i class="ki-outline ki-cloud-upload fs-2 me-2"></i>
                                        Upload Bukti Transfer
                                    </h4>
                                </div>
                                <div class="card-body">
                                    @if ($subscription->payment_proof)
                                        <div class="alert alert-success d-flex align-items-center mb-5">
                                            <i class="ki-outline ki-check-circle fs-2 text-success me-3"></i>
                                            <div>
                                                <div class="fw-bold">Bukti pembayaran sudah diupload</div>
                                                <div class="text-muted fs-7">Menunggu verifikasi dari admin</div>
                                            </div>
                                        </div>
                                        <div class="text-center mb-5">
                                            <img src="{{ asset('storage/' . $subscription->payment_proof) }}"
                                                alt="Payment Proof" class="img-fluid rounded border"
                                                style="max-height: 300px;">
                                        </div>
                                    @endif

                                    <form action="{{ route('subscription.manual.upload-proof', $subscription) }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="mb-7">
                                            <label class="form-label required">Bukti Transfer (JPG, PNG, max 2MB)</label>
                                            <input type="file" name="payment_proof" accept="image/*"
                                                class="form-control form-control-lg" required>
                                            <div class="form-text">Upload foto/screenshot bukti transfer Anda</div>
                                            @error('payment_proof')
                                                <div class="text-danger mt-2">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-end gap-3">
                                            <a href="{{ route('subscriptions.index') }}" class="btn btn-light">
                                                Batal
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ki-outline ki-cloud-upload fs-4 me-2"></i>
                                                {{ $subscription->payment_proof ? 'Upload Ulang' : 'Upload Bukti' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
