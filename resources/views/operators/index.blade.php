@extends('layout.app')

@section('title', 'Kelola Operator')

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

    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <h3 class="fw-bold m-0">Daftar Operator</h3>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('operators.create') }}" class="btn btn-sm btn-primary">
                    <i class="ki-outline ki-plus fs-4 me-1"></i>
                    Tambah Operator
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-50px">No</th>
                            <th class="min-w-200px">Nama</th>
                            <th class="min-w-150px">Email</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-120px">Terdaftar</th>
                            <th class="min-w-120px text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($operators as $index => $operator)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-40px me-3">
                                            <span class="symbol-label bg-light-info text-info fw-bold">
                                                {{ substr($operator->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bold fs-6">{{ $operator->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold d-block">{{ $operator->email }}</span>
                                </td>
                                <td>
                                    @if ($operator->pivot->is_active)
                                        <span class="badge badge-light-success">Aktif</span>
                                    @else
                                        <span class="badge badge-light-danger">Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="text-muted fw-semibold d-block">{{ $operator->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="text-end">
                                    <form action="{{ route('operators.toggle-status', $operator) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-icon btn-light-{{ $operator->pivot->is_active ? 'warning' : 'success' }} btn-sm me-1"
                                            title="{{ $operator->pivot->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i
                                                class="ki-outline ki-{{ $operator->pivot->is_active ? 'cross-circle' : 'check-circle' }} fs-4"></i>
                                        </button>
                                    </form>
                                    <a href="{{ route('operators.edit', $operator) }}"
                                        class="btn btn-icon btn-light-primary btn-sm me-1" title="Edit">
                                        <i class="ki-outline ki-pencil fs-4"></i>
                                    </a>
                                    <form action="{{ route('operators.destroy', $operator) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus operator ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-light-danger btn-sm" title="Hapus">
                                            <i class="ki-outline ki-trash fs-4"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-10">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="ki-duotone ki-user fs-5x text-muted mb-3">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        <span class="fs-5 fw-semibold">Belum ada operator</span>
                                        <span class="fs-7 text-muted mt-1">Klik tombol "Tambah Operator" untuk menambahkan
                                            operator baru</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
