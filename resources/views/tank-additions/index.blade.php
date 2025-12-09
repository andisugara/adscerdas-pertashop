@extends('layout.app')

@section('title', 'Penambahan Tangki')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penambahan Tangki (Delivery Order)</h3>
            <div class="card-toolbar">
                <a href="{{ route('tank-additions.create') }}" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah DO
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-5">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
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
                            <th>Operator</th>
                            <th class="text-end">Jumlah (Liter)</th>
                            <th>Keterangan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tankAdditions as $item)
                            <tr>
                                <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td class="text-end">{{ formatNumber($item->jumlah_liter) }}</td>
                                <td>{{ $item->keterangan ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('tank-additions.edit', $item->id) }}"
                                        class="btn btn-sm btn-light-primary">
                                        <i class="ki-outline ki-pencil fs-5"></i>
                                    </a>
                                    @if (auth()->user()->isOwner())
                                        <form action="{{ route('tank-additions.destroy', $item->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
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
                                <td colspan="5" class="text-center text-gray-600">Tidak ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-5">
                {{ $tankAdditions->links() }}
            </div>
        </div>
    </div>
@endsection
