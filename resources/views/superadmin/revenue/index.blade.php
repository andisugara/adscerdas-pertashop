@extends('superadmin.layout')

@section('title', 'Laporan Pendapatan')

@section('content')
    <!-- Filter Card -->
    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.revenue.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Tahun</label>
                    <select name="year" class="form-select">
                        @foreach ($availableYears as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Bulan (Opsional)</label>
                    <select name="month" class="form-select">
                        <option value="">Semua Bulan</option>
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-filter fs-4 me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('superadmin.revenue.index') }}" class="btn btn-light">
                        <i class="ki-outline ki-cross fs-4 me-1"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-5 g-xl-8 mb-5 mb-xl-8">
        <!-- Total Revenue -->
        <div class="col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="fs-2hx fw-bold text-gray-800 mb-2 lh-1">
                            Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                        </div>
                        <div class="fs-6 fw-semibold text-gray-400">Total Pendapatan</div>
                    </div>
                    <div class="mt-5">
                        <i class="ki-duotone ki-wallet fs-3x text-primary">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                        </i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Yearly Revenue -->
        <div class="col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="fs-2hx fw-bold text-gray-800 mb-2 lh-1">
                            Rp {{ number_format($stats['yearly_revenue'], 0, ',', '.') }}
                        </div>
                        <div class="fs-6 fw-semibold text-gray-400">Pendapatan {{ $year }}</div>
                    </div>
                    <div class="mt-5">
                        <i class="ki-duotone ki-calendar fs-3x text-success">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue (if filtered) -->
        @if ($month)
            <div class="col-xl-3">
                <div class="card card-flush h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <div class="fs-2hx fw-bold text-gray-800 mb-2 lh-1">
                                Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}
                            </div>
                            <div class="fs-6 fw-semibold text-gray-400">
                                {{ \Carbon\Carbon::create()->month($month)->format('F') }} {{ $year }}
                            </div>
                        </div>
                        <div class="mt-5">
                            <i class="ki-duotone ki-chart-simple fs-3x text-info">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Total Transactions -->
        <div class="col-xl-3">
            <div class="card card-flush h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div>
                        <div class="fs-2hx fw-bold text-gray-800 mb-2 lh-1">
                            {{ number_format($stats['total_paid_subscriptions'], 0, ',', '.') }}
                        </div>
                        <div class="fs-6 fw-semibold text-gray-400">Total Transaksi</div>
                    </div>
                    <div class="mt-5">
                        <i class="ki-duotone ki-receipt-square fs-3x text-warning">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Chart -->
    <div class="card mb-5">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Grafik Pendapatan Bulanan {{ $year }}</span>
            </h3>
        </div>
        <div class="card-body">
            <div id="revenue_chart" style="height: 350px;"></div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Daftar Transaksi</span>
                <span class="text-muted mt-1 fw-semibold fs-7">{{ $subscriptions->total() }} transaksi</span>
            </h3>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-50px">No</th>
                            <th class="min-w-150px">Tanggal</th>
                            <th class="min-w-200px">Organisasi</th>
                            <th class="min-w-100px">Paket</th>
                            <th class="min-w-120px">Harga</th>
                            <th class="min-w-120px">Metode</th>
                            <th class="min-w-100px">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subscriptions as $index => $sub)
                            <tr>
                                <td>{{ $subscriptions->firstItem() + $index }}</td>
                                <td>
                                    <span class="text-dark fw-bold d-block">{{ $sub->approved_at->format('d M Y') }}</span>
                                    <span class="text-muted fs-7">{{ $sub->approved_at->format('H:i') }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-light-primary text-primary fw-bold">
                                                {{ substr($sub->organization->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bold fs-6">{{ $sub->organization->name }}</span>
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
                                    <span class="text-dark fw-bold d-block">Rp
                                        {{ number_format($sub->price, 0, ',', '.') }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light">{{ ucfirst($sub->payment_method) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-success">Paid</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Tidak ada data transaksi</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($subscriptions->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $subscriptions->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        var monthlyData = @json($monthlyData);

        var options = {
            series: [{
                name: 'Pendapatan',
                data: monthlyData.map(item => item.revenue)
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            colors: ['#3E97FF'],
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            xaxis: {
                categories: monthlyData.map(item => item.month_name),
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: '#A1A5B7',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    },
                    style: {
                        colors: '#A1A5B7',
                        fontSize: '12px'
                    }
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "Rp " + val.toLocaleString('id-ID')
                    }
                }
            },
            grid: {
                borderColor: '#F1F1F2',
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#revenue_chart"), options);
        chart.render();
    </script>
@endpush
