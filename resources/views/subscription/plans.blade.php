@extends('layout.app')

@section('title', 'Pilih Paket Subscription')

@section('content')
    <div class="row g-5 g-xl-10">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pilih Paket Subscription</h3>
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
                        <!-- Monthly Plan -->
                        <div class="col-lg-6">
                            <div class="card border border-gray-300 h-100">
                                <div class="card-body p-10">
                                    <div class="mb-7">
                                        <h2 class="fw-bold text-gray-800 mb-3">Paket Bulanan</h2>
                                        <div class="d-flex align-items-baseline mb-3">
                                            <span class="fs-2hx fw-bold text-primary me-2">Rp
                                                {{ number_format($monthlyPrice, 0, ',', '.') }}</span>
                                            <span class="text-gray-600 fw-semibold">/bulan</span>
                                        </div>
                                        <p class="text-gray-600 mb-0">Cocok untuk mencoba layanan dengan komitmen jangka
                                            pendek</p>
                                    </div>

                                    <div class="separator my-7"></div>

                                    <form action="{{ route('subscription.subscribe') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan" value="monthly">

                                        <div class="mb-7">
                                            <label class="form-label required">Metode Pembayaran</label>
                                            <select name="payment_method" class="form-select form-select-solid" required>
                                                <option value="">-- Pilih Metode --</option>
                                                <option value="manual">Transfer Manual</option>
                                                <option value="duitku">Duitku (Otomatis)</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                                            <i class="ki-outline ki-check-circle fs-3 me-2"></i>
                                            Langganan Bulanan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Yearly Plan -->
                        <div class="col-lg-6">
                            <div class="card border-2 border-primary h-100 position-relative">
                                <div class="ribbon ribbon-triangle ribbon-top-start border-primary">
                                    <div class="ribbon-icon mt-n5 ms-n6">
                                        <i class="ki-outline ki-rocket fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="card-body p-10">
                                    <div class="mb-7">
                                        <div class="d-flex align-items-center mb-3">
                                            <h2 class="fw-bold text-gray-800 mb-0 me-3">Paket Tahunan</h2>
                                            <span class="badge badge-success fs-7">HEMAT</span>
                                        </div>
                                        <div class="d-flex align-items-baseline mb-3">
                                            <span class="fs-2hx fw-bold text-primary me-2">Rp
                                                {{ number_format($yearlyPrice, 0, ',', '.') }}</span>
                                            <span class="text-gray-600 fw-semibold">/tahun</span>
                                        </div>
                                        <div class="alert alert-success p-3 mb-3">
                                            <div class="d-flex align-items-center">
                                                <i class="ki-outline ki-check-circle fs-2x text-success me-3"></i>
                                                <div>
                                                    <div class="fw-bold">Hemat Rp
                                                        {{ number_format($monthlyPrice * 12 - $yearlyPrice, 0, ',', '.') }}
                                                    </div>
                                                    <div class="text-gray-700 fs-7">Dibanding langganan bulanan</div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-600 mb-0">Pilihan terbaik untuk bisnis jangka panjang dengan
                                            penghematan maksimal</p>
                                    </div>

                                    <div class="separator my-7"></div>

                                    <form action="{{ route('subscription.subscribe') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="plan" value="yearly">

                                        <div class="mb-7">
                                            <label class="form-label required">Metode Pembayaran</label>
                                            <select name="payment_method" class="form-select form-select-solid" required>
                                                <option value="">-- Pilih Metode --</option>
                                                <option value="manual">Transfer Manual</option>
                                                <option value="duitku">Duitku (Otomatis)</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                                            <i class="ki-outline ki-check-circle fs-3 me-2"></i>
                                            Langganan Tahunan
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features Comparison -->
                    <div class="mt-10">
                        <div class="separator my-10"></div>
                        <h3 class="text-center fw-bold text-gray-800 mb-7">Fitur yang Anda Dapatkan</h3>
                        <div class="row g-5">
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Laporan Lengkap</h5>
                                        <p class="text-gray-600 mb-0">Dashboard analitik dan laporan penjualan real-time</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Multi Operator</h5>
                                        <p class="text-gray-600 mb-0">Tambahkan operator tanpa batas untuk pertashop Anda
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-start">
                                    <i class="ki-outline ki-check-circle fs-1 text-success me-3"></i>
                                    <div>
                                        <h5 class="fw-bold mb-1">Support 24/7</h5>
                                        <p class="text-gray-600 mb-0">Dukungan pelanggan siap membantu kapan saja</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
