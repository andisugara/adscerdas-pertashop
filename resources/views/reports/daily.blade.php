@extends('layout.app')

@section('title', 'Laporan Harian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Rekapan Pertashop Per Hari</h3>
            <div class="card-toolbar">
                <form action="{{ route('reports.daily') }}" method="GET">
                    <div class="d-flex align-items-center gap-2">
                        <input type="month" name="month" class="form-control" value="{{ $month }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="ki-outline ki-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th rowspan="2" class="text-center">TANGGAL</th>
                            <th rowspan="2" class="text-end">Totalisator Awal</th>
                            <th rowspan="2" class="text-end">Totalisator Akhir</th>
                            <th colspan="2" class="text-center">SALES</th>
                            <th colspan="2" class="text-center">STOCK AWAL</th>
                            <th colspan="2" class="text-center">STOCK AKHIR</th>
                            <th colspan="2" class="text-center">LOSSES</th>
                            <th rowspan="2" class="text-end">Penambahan DO<br>(Liter)</th>
                            <th rowspan="2" class="text-end">STOK AWAL<br>+ DO (Liter)</th>
                            <th rowspan="2" class="text-end">Stok Awal<br>- Stok Akhir</th>
                            <th rowspan="2" class="text-end">MARGIN / Hari</th>
                        </tr>
                        <tr>
                            <th class="text-end">Liter</th>
                            <th class="text-end">Rupiah</th>
                            <th class="text-end">mm</th>
                            <th class="text-end">Liter</th>
                            <th class="text-end">mm</th>
                            <th class="text-end">Liter</th>
                            <th class="text-end">Liter</th>
                            <th class="text-end">Rupiah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dailyData as $data)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($data['date'])->format('d/m/Y') }}</td>
                                <td class="text-end">{{ formatNumber($data['ta'], 3) }}</td>
                                <td class="text-end">{{ formatNumber($data['tak'], 3) }}</td>
                                <td class="text-end fw-bold">{{ formatNumber($data['salesLiter'], 3) }}</td>
                                <td class="text-end">{{ formatRupiah($data['salesRp']) }}</td>
                                <td class="text-end">{{ formatNumber($data['sa']) }}</td>
                                <td class="text-end">{{ formatNumber($data['sal']) }}</td>
                                <td class="text-end">{{ formatNumber($data['sak']) }}</td>
                                <td class="text-end">{{ formatNumber($data['sakl']) }}</td>
                                <td class="text-end fw-bold {{ $data['ll'] < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ formatNumber($data['ll'], 3) }}
                                </td>
                                <td class="text-end {{ $data['lr'] < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ formatRupiah($data['lr']) }}
                                </td>
                                <td class="text-end">{{ formatNumber($data['tankAdditions'], 3) }}</td>
                                <td class="text-end">{{ formatNumber($data['stokAwalDO']) }}</td>
                                <td class="text-end">{{ formatNumber($data['selisihStok']) }}</td>
                                <td class="text-end fw-bold text-success">{{ formatRupiah($data['marginHari']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="15" class="text-center text-muted py-5">
                                    Belum ada data untuk bulan ini
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
