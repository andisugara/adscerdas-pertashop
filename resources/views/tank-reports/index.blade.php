@extends('layout.app')

@section('title', 'Laporan Tangki')

@section('content')
    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">Laporan Monitoring Tangki & Loses</h3>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-5">
                <div class="col-md-12">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Atau Pilih Bulan</label>
                            <input type="month" name="month" class="form-control" value="{{ request('month') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ki-outline ki-filter"></i> Filter
                            </button>
                            <a href="{{ route('tank-reports.index') }}" class="btn btn-light">
                                <i class="ki-outline ki-arrow-rotate-left"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row g-5 mb-5">
                <div class="col-md-4">
                    <div class="card bg-light-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">
                                    <span class="symbol-label bg-primary">
                                        <i class="ki-outline ki-delivery-3 fs-2x text-white"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-700 fw-semibold d-block fs-7">Total Pengisian</span>
                                    <span class="text-gray-900 fw-bold fs-2">{{ formatNumber($totalPengisian) }} L</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card {{ $totalLoses < 0 ? 'bg-light-danger' : ($totalLoses > 0 ? 'bg-light-warning' : 'bg-light-success') }}">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">
                                    <span class="symbol-label {{ $totalLoses < 0 ? 'bg-danger' : ($totalLoses > 0 ? 'bg-warning' : 'bg-success') }}">
                                        <i class="ki-outline ki-chart-simple-3 fs-2x text-white"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-700 fw-semibold d-block fs-7">Total Loses</span>
                                    <span class="text-gray-900 fw-bold fs-2">{{ formatNumber($totalLoses) }} L</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-5">
                                    <span class="symbol-label bg-info">
                                        <i class="ki-outline ki-calculator fs-2x text-white"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="text-gray-700 fw-semibold d-block fs-7">Rata-rata Loses</span>
                                    <span class="text-gray-900 fw-bold fs-2">{{ formatNumber($avgLoses) }} L</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-4">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th>Tanggal</th>
                            <th>Shipment No.</th>
                            <th>Pengemudi</th>
                            <th class="text-end">Stok Awal (MM)</th>
                            <th class="text-end">SAL (Liter)</th>
                            <th class="text-end">Pengisian (L)</th>
                            <th class="text-end">Stok Akhir (MM)</th>
                            <th class="text-end">SAKL (Liter)</th>
                            <th class="text-end">Loses (L)</th>
                            <th class="text-center">Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tankReports as $report)
                            <tr>
                                <td>{{ $report->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $report->shipment_no ?? '-' }}</td>
                                <td>{{ $report->nama_pengemudi ?? '-' }}</td>
                                <td class="text-end">{{ formatNumber($report->stok_awal) }}</td>
                                <td class="text-end text-info">{{ formatNumber($report->stok_awal_liter) }}</td>
                                <td class="text-end fw-bold">{{ formatNumber($report->jumlah_liter) }}</td>
                                <td class="text-end">{{ formatNumber($report->stok_akhir) }}</td>
                                <td class="text-end text-info">{{ formatNumber($report->stok_akhir_liter) }}</td>
                                <td class="text-end fw-bold {{ $report->loses < 0 ? 'text-danger' : ($report->loses > 0 ? 'text-warning' : 'text-success') }}">
                                    {{ formatNumber($report->loses) }}
                                </td>
                                <td class="text-center">
                                    @if($report->loses < 0)
                                        <span class="badge badge-danger">Susut</span>
                                    @elseif($report->loses > 0)
                                        <span class="badge badge-warning">Lebih</span>
                                    @else
                                        <span class="badge badge-success">Normal</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tank-additions.show', $report->id) }}" class="btn btn-sm btn-light-primary">
                                        <i class="ki-outline ki-eye fs-5"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-gray-600">Tidak ada data laporan tangki</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($tankReports->count() > 0)
                    <tfoot>
                        <tr class="fw-bold bg-light">
                            <td colspan="5" class="text-end">Total:</td>
                            <td class="text-end">{{ formatNumber($tankReports->sum('jumlah_liter')) }} L</td>
                            <td colspan="2"></td>
                            <td class="text-end {{ $tankReports->sum(fn($r) => $r->loses) < 0 ? 'text-danger' : 'text-success' }}">
                                {{ formatNumber($tankReports->sum(fn($r) => $r->loses)) }} L
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <div class="mt-5">
                {{ $tankReports->links() }}
            </div>
        </div>
    </div>

    <!-- Analisa Section -->
    @if($analysisByNopol->count() > 0 || $analysisByDriver->count() > 0)
    <div class="row g-5 mt-2">
        <!-- Analisa per No. Polisi -->
        @if($analysisByNopol->count() > 0)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Analisa per No. Polisi (Top 10 Loses Tertinggi)</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered align-middle gy-3">
                            <thead>
                                <tr class="fw-bold fs-7 text-gray-800">
                                    <th>No. Polisi</th>
                                    <th class="text-center">Frekuensi</th>
                                    <th class="text-end">Total Pengisian</th>
                                    <th class="text-end">Total Loses</th>
                                    <th class="text-end">Avg Loses</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analysisByNopol as $analysis)
                                <tr>
                                    <td class="fw-bold">{{ $analysis['no_polisi'] }}</td>
                                    <td class="text-center">{{ $analysis['count'] }}x</td>
                                    <td class="text-end">{{ formatNumber($analysis['total_pengisian']) }} L</td>
                                    <td class="text-end {{ $analysis['total_loses'] < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ formatNumber($analysis['total_loses']) }} L
                                    </td>
                                    <td class="text-end fw-bold {{ $analysis['avg_loses'] < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ formatNumber($analysis['avg_loses']) }} L
                                    </td>
                                    <td class="text-center">
                                        @if($analysis['status'] == 'danger')
                                            <span class="badge badge-danger">
                                                <i class="ki-outline ki-cross-circle fs-7"></i> Tinggi
                                            </span>
                                        @elseif($analysis['status'] == 'warning')
                                            <span class="badge badge-warning">
                                                <i class="ki-outline ki-information-4 fs-7"></i> Sedang
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="ki-outline ki-check-circle fs-7"></i> Normal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-dismissible bg-light-warning border border-warning border-dashed d-flex flex-column flex-sm-row p-5 mt-5">
                        <i class="ki-outline ki-information-4 fs-2hx text-warning me-4 mb-5 mb-sm-0"></i>
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h5 class="mb-1">Perhatian!</h5>
                            <span>
                                - Status <strong>TINGGI</strong>: Loses rata-rata < -20 Liter (perlu perhatian khusus)<br>
                                - Status <strong>SEDANG</strong>: Loses rata-rata < -10 Liter (perlu monitoring)<br>
                                - Status <strong>NORMAL</strong>: Loses dalam batas wajar
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Analisa per Pengemudi -->
        @if($analysisByDriver->count() > 0)
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Analisa per Pengemudi (Top 10 Loses Tertinggi)</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-row-bordered align-middle gy-3">
                            <thead>
                                <tr class="fw-bold fs-7 text-gray-800">
                                    <th>Nama Pengemudi</th>
                                    <th class="text-center">Frekuensi</th>
                                    <th class="text-end">Total Pengisian</th>
                                    <th class="text-end">Total Loses</th>
                                    <th class="text-end">Avg Loses</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analysisByDriver as $analysis)
                                <tr>
                                    <td class="fw-bold">{{ $analysis['nama_pengemudi'] }}</td>
                                    <td class="text-center">{{ $analysis['count'] }}x</td>
                                    <td class="text-end">{{ formatNumber($analysis['total_pengisian']) }} L</td>
                                    <td class="text-end {{ $analysis['total_loses'] < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ formatNumber($analysis['total_loses']) }} L
                                    </td>
                                    <td class="text-end fw-bold {{ $analysis['avg_loses'] < 0 ? 'text-danger' : 'text-success' }}">
                                        {{ formatNumber($analysis['avg_loses']) }} L
                                    </td>
                                    <td class="text-center">
                                        @if($analysis['status'] == 'danger')
                                            <span class="badge badge-danger">
                                                <i class="ki-outline ki-cross-circle fs-7"></i> Tinggi
                                            </span>
                                        @elseif($analysis['status'] == 'warning')
                                            <span class="badge badge-warning">
                                                <i class="ki-outline ki-information-4 fs-7"></i> Sedang
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="ki-outline ki-check-circle fs-7"></i> Normal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-dismissible bg-light-info border border-info border-dashed d-flex flex-column flex-sm-row p-5 mt-5">
                        <i class="ki-outline ki-information fs-2hx text-info me-4 mb-5 mb-sm-0"></i>
                        <div class="d-flex flex-column pe-0 pe-sm-10">
                            <h5 class="mb-1">Info</h5>
                            <span>
                                Pengemudi dengan loses tinggi perlu dievaluasi untuk memastikan prosedur pengisian yang benar dan menghindari kecurangan.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
@endsection
