@extends('layout.app')

@section('title', 'Laporan Harian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Harian Tangki</h3>
            <div class="card-toolbar">
                <a href="{{ route('daily-reports.create') }}" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i> Input Laporan
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Filter -->
            <div class="mb-5">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}"
                            placeholder="Filter tanggal">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-light-primary">
                            <i class="ki-outline ki-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-4">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th>Tanggal</th>
                            <th>Shift</th>
                            <th>Operator</th>
                            <th class="text-end">TA</th>
                            <th class="text-end">TAK</th>
                            <th class="text-end">SA (MM)</th>
                            <th class="text-end">SAK (MM)</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reports as $report)
                            <tr>
                                <td>{{ $report->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $report->shift->nama_shift }}</td>
                                <td>{{ $report->user->name }}</td>
                                <td class="text-end">{{ formatNumber($report->totalisator_awal, 3) }}</td>
                                <td class="text-end">{{ formatNumber($report->totalisator_akhir, 3) }}</td>
                                <td class="text-end">{{ formatNumber($report->stok_awal_mm) }}</td>
                                <td class="text-end">{{ formatNumber($report->stok_akhir_mm) }}</td>
                                <td class="text-end">
                                    <a href="{{ route('daily-reports.edit', $report->id) }}"
                                        class="btn btn-sm btn-light-primary">
                                        <i class="ki-outline ki-pencil fs-5"></i>
                                    </a>
                                    @if (auth()->user()->isOwner())
                                        <form action="{{ route('daily-reports.destroy', $report->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin hapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light-danger">
                                                <i class="ki-outline ki-trash fs-5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-gray-600">Tidak ada data laporan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
@endsection
