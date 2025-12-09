@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Filter Bulan -->
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('dashboard') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Filter Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ $month }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-duotone ki-search-list fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                                Tampilkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <!-- Summary Cards Bulan Ini -->
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Total Sales</span>
                    <div class="fs-2hx fw-bold text-primary mt-2">{{ formatNumber($monthlyData['sales'], 3) }}</div>
                    <span class="text-gray-500 fs-7">Liter Bulan Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Margin Kotor</span>
                    <div class="fs-2hx fw-bold text-success mt-2">{{ formatRupiah($monthlyData['marginKotor']) }}</div>
                    <span class="text-gray-500 fs-7">Bulan Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Profit</span>
                    <div class="fs-2hx fw-bold text-info mt-2">{{ formatRupiah($monthlyData['profit']) }}</div>
                    <span class="text-gray-500 fs-7">Bulan Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Loses</span>
                    <div class="fs-2hx fw-bold text-warning mt-2">{{ formatNumber($monthlyData['losesLiter'], 3) }}</div>
                    <span class="text-gray-500 fs-7">Liter Bulan Ini</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Cards -->
    <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Gaji</span>
                    <div class="fs-2hx fw-bold text-dark mt-2">{{ formatRupiah($monthlyData['gaji']) }}</div>
                    <span class="text-gray-500 fs-7">Bulan Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Zakat (2.5%)</span>
                    <div class="fs-2hx fw-bold text-dark mt-2">{{ formatRupiah($monthlyData['zakat']) }}</div>
                    <span class="text-gray-500 fs-7">Bulan Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Operasional</span>
                    <div class="fs-2hx fw-bold text-dark mt-2">{{ formatRupiah($monthlyData['operasional']) }}</div>
                    <span class="text-gray-500 fs-7">Bulan Ini</span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-flush h-100">
                <div class="card-body">
                    <span class="text-gray-600 fw-semibold fs-6">Rata-rata Sales/Hari</span>
                    <div class="fs-2hx fw-bold text-primary mt-2">
                        {{ formatNumber($monthlyData['rataRataPenjualanPerHari'], 3) }}</div>
                    <span class="text-gray-500 fs-7">Liter/Hari</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Ringkasan -->
    <div class="row g-5 g-xl-10">
        <!-- Grafik Penjualan -->
        <div class="col-xl-6">
            <div class="card card-flush h-100">
                <div class="card-header">
                    <h3 class="card-title">Penjualan 7 Hari Terakhir</h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="150"></canvas>
                </div>
            </div>
        </div>

        <!-- Summary Cards Kiri -->
        <div class="col-xl-3">
            <div class="row g-5">
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">HPP/Liter</span>
                            </div>
                            <div class="fs-3 fw-bold text-dark">{{ formatRupiah($monthlyData['hppPerLiter']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">HPP Total</span>
                            </div>
                            <div class="fs-3 fw-bold text-dark">{{ formatRupiah($monthlyData['hppRp']) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">Sales (Rp)</span>
                            </div>
                            <div class="fs-3 fw-bold text-success">
                                {{ formatRupiah($monthlyData['sales'] * $setting->harga_jual) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">Losses (Rp)</span>
                            </div>
                            <div class="fs-3 fw-bold {{ $monthlyData['losesRp'] < 0 ? 'text-danger' : 'text-warning' }}">
                                {{ formatRupiah($monthlyData['losesRp']) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards Kanan -->
        <div class="col-xl-3">
            <div class="row g-5">
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">Pembelian (DO)</span>
                            </div>
                            <div class="fs-3 fw-bold text-dark">{{ formatNumber($monthlyData['pembelian'], 3) }}</div>
                            <span class="text-gray-500 fs-8">Liter</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">Penjualan</span>
                            </div>
                            <div class="fs-3 fw-bold text-dark">{{ formatNumber($monthlyData['penjualan'], 3) }}</div>
                            <span class="text-gray-500 fs-8">Liter</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">Stok Awal</span>
                            </div>
                            <div class="fs-3 fw-bold text-dark">{{ formatNumber($monthlyData['stokAwal'], 3) }}</div>
                            <span class="text-gray-500 fs-8">mm</span>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card card-flush">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-center mb-2">
                                <span class="text-gray-600 fw-semibold fs-7">Stok Akhir</span>
                            </div>
                            <div class="fs-3 fw-bold text-dark">{{ formatNumber($monthlyData['stokAkhir'], 3) }}</div>
                            <span class="text-gray-500 fs-8">mm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="row g-5 g-xl-10 mt-5">
        <div class="col-xl-12">
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">Detail Perhitungan Bulan Ini</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-sm">
                                    <tr class="table-active">
                                        <td colspan="2" class="fw-bold text-center">TOTAL KESELURUHAN</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">HPP / Liter</td>
                                        <td class="text-end fw-bold">{{ formatRupiah($monthlyData['hppPerLiter']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">HPP (Rp)</td>
                                        <td class="text-end fw-bold">{{ formatRupiah($monthlyData['hppRp']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Sales (Liter)</td>
                                        <td class="text-end fw-bold">{{ formatNumber($monthlyData['sales'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Sales (Rp)</td>
                                        <td class="text-end fw-bold">
                                            {{ formatRupiah($monthlyData['sales'] * $setting->harga_jual) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Losses (Liter)</td>
                                        <td
                                            class="text-end fw-bold {{ $monthlyData['losesLiter'] < 0 ? 'text-danger' : '' }}">
                                            {{ formatNumber($monthlyData['losesLiter'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Losses (Rp)</td>
                                        <td
                                            class="text-end fw-bold {{ $monthlyData['losesRp'] < 0 ? 'text-danger' : '' }}">
                                            {{ formatRupiah($monthlyData['losesRp']) }}</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td class="fw-bold">Margin Kotor</td>
                                        <td class="text-end fw-bold">{{ formatRupiah($monthlyData['marginKotor']) }}</td>
                                    </tr>
                                    <tr class="table-active">
                                        <td colspan="2" class="fw-bold text-center">PERHITUNGAN PROFIT</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Margin Kotor</td>
                                        <td class="text-end">{{ formatRupiah($monthlyData['marginKotor']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Operasional</td>
                                        <td class="text-end">{{ formatRupiah($monthlyData['operasional']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Gaji</td>
                                        <td class="text-end">{{ formatRupiah($monthlyData['gaji']) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Zakat (2.5%)</td>
                                        <td class="text-end">{{ formatRupiah($monthlyData['zakat']) }}</td>
                                    </tr>
                                    <tr class="table-info">
                                        <td class="fw-bold">PROFIT</td>
                                        <td class="text-end fw-bold">{{ formatRupiah($monthlyData['profit']) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-row-bordered table-sm">
                                    <tr class="table-active">
                                        <td colspan="2" class="fw-bold text-center">STOK & LOSSES</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Pembelian (DO)</td>
                                        <td class="text-end">{{ formatNumber($monthlyData['pembelian'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Penjualan</td>
                                        <td class="text-end">{{ formatNumber($monthlyData['penjualan'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Selisih (Pem - Penj)</td>
                                        <td class="text-end fw-bold">
                                            {{ formatNumber($monthlyData['selisihPenjualan'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Stok Awal (mm)</td>
                                        <td class="text-end">{{ formatNumber($monthlyData['stokAwal'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Stok Akhir (mm)</td>
                                        <td class="text-end">{{ formatNumber($monthlyData['stokAkhir'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Selisih Stok (SA - SAK)</td>
                                        <td class="text-end fw-bold">{{ formatNumber($monthlyData['selisihStok'], 3) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Sisa Stok Real</td>
                                        <td class="text-end">{{ formatNumber($monthlyData['sisaStokReal'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Sisa Stok Beli (DO)</td>
                                        <td class="text-end">{{ formatNumber($monthlyData['sisaStokBeli'], 3) }}</td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td class="fw-bold">Hilang / Losses</td>
                                        <td class="text-end fw-bold">{{ formatNumber($monthlyData['loses'], 3) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-gray-600">Rata-rata Sales/Hari</td>
                                        <td class="text-end fw-bold">
                                            {{ formatNumber($monthlyData['rataRataPenjualanPerHari'], 3) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesData = @json($last7Days);

        const labels = salesData.map(item => {
            const date = new Date(item.date);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short'
            });
        });

        const data = salesData.map(item => item.sales);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Penjualan (Liter)',
                    data: data,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString('id-ID', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 3
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
