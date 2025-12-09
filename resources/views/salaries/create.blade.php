@extends('layout.app')

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Tambah
                        Gaji</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('salaries.index') }}" class="text-muted text-hover-primary">Data Gaji</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Tambah</li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('salaries.store') }}" method="POST">
                            @csrf

                            <div class="mb-10">
                                <label class="form-label required">Bulan</label>
                                <input type="month" name="bulan"
                                    class="form-control @error('bulan') is-invalid @enderror" value="{{ old('bulan') }}"
                                    required>
                                @error('bulan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Pilih bulan untuk data gaji</div>
                            </div>

                            <div class="mb-10">
                                <label class="form-label required">Jumlah Gaji</label>
                                <input type="text" name="jumlah"
                                    class="form-control decimal-input @error('jumlah') is-invalid @enderror"
                                    value="{{ old('jumlah') }}" placeholder="0" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Total gaji untuk bulan tersebut</div>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                                <div class="form-text">Catatan tambahan (opsional)</div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('salaries.index') }}" class="btn btn-light me-3">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decimalInputs = document.querySelectorAll('.decimal-input');

            decimalInputs.forEach(input => {
                // Format on blur
                input.addEventListener('blur', function() {
                    let value = this.value.replace(/\./g, '').replace(',', '.');
                    if (value && !isNaN(value)) {
                        this.value = parseFloat(value).toLocaleString('id-ID');
                    }
                });

                // Remove separator on focus
                input.addEventListener('focus', function() {
                    this.value = this.value.replace(/\./g, '');
                });
            });
        });
    </script>
@endpush
