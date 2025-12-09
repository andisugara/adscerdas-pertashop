@extends('layout.app')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Data
                        Gaji</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Data Gaji</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ route('salaries.create') }}" class="btn btn-sm btn-primary">
                        <i class="ki-duotone ki-plus fs-2"></i>
                        Tambah Gaji
                    </a>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="fw-bold text-muted">
                                        <th class="min-w-100px">Bulan</th>
                                        <th class="min-w-150px">Jumlah Gaji</th>
                                        <th class="min-w-200px">Keterangan</th>
                                        <th class="min-w-100px text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($salaries as $salary)
                                        <tr>
                                            <td>
                                                <span
                                                    class="fw-bold">{{ \Carbon\Carbon::parse($salary->bulan . '-01')->isoFormat('MMMM YYYY') }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-bold text-success">{{ formatRupiah($salary->jumlah) }}</span>
                                            </td>
                                            <td>
                                                <span class="text-gray-800">{{ $salary->keterangan ?? '-' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('salaries.edit', $salary) }}"
                                                    class="btn btn-icon btn-light btn-sm me-1">
                                                    <i class="ki-duotone ki-pencil fs-3">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>
                                                </a>
                                                <form action="{{ route('salaries.destroy', $salary) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus data gaji ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-icon btn-light btn-sm">
                                                        <i class="ki-duotone ki-trash fs-3">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                            <span class="path3"></span>
                                                            <span class="path4"></span>
                                                            <span class="path5"></span>
                                                        </i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada data gaji</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            {{ $salaries->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
