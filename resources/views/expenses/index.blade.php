@extends('layout.app')

@section('title', 'Pengeluaran')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pengeluaran</h3>
            <div class="card-toolbar">
                <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah Pengeluaran
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
                            <th>Nama Pengeluaran</th>
                            <th class="text-end">Jumlah</th>
                            <th>Keterangan</th>
                            <th>Input By</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ $expense->tanggal->format('d/m/Y') }}</td>
                                <td>{{ $expense->nama_pengeluaran }}</td>
                                <td class="text-end">{{ formatRupiah($expense->jumlah) }}</td>
                                <td>{{ $expense->keterangan ?? '-' }}</td>
                                <td>{{ $expense->user->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('expenses.edit', $expense->id) }}"
                                        class="btn btn-sm btn-light-primary">
                                        <i class="ki-outline ki-pencil fs-5"></i>
                                    </a>
                                    @if (auth()->user()->isOwner())
                                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin hapus pengeluaran ini?')">
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
                                <td colspan="6" class="text-center text-gray-600">Tidak ada data pengeluaran</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2" class="text-end">Total:</td>
                            <td class="text-end">{{ formatRupiah($expenses->sum('jumlah')) }}</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-5">
                {{ $expenses->links() }}
            </div>
        </div>
    </div>
@endsection
