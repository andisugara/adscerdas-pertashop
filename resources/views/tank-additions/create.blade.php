@extends('layout.app')

@section('title', 'Tambah Penambahan Tangki')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Penambahan Tangki (DO)</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('tank-additions.store') }}" method="POST">
                @csrf

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Tanggal</label>
                    <div class="col-lg-9">
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">No. Polisi</label>
                    <div class="col-lg-9">
                        <input type="text" name="no_polisi"
                            class="form-control @error('no_polisi') is-invalid @enderror"
                            value="{{ old('no_polisi') }}" placeholder="Contoh: B 1234 XYZ">
                        @error('no_polisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Shipment No.</label>
                    <div class="col-lg-9">
                        <input type="text" name="shipment_no"
                            class="form-control @error('shipment_no') is-invalid @enderror"
                            value="{{ old('shipment_no') }}" placeholder="Contoh: SHP-2025-001">
                        @error('shipment_no')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Nama Pengemudi</label>
                    <div class="col-lg-9">
                        <input type="text" name="nama_pengemudi"
                            class="form-control @error('nama_pengemudi') is-invalid @enderror"
                            value="{{ old('nama_pengemudi') }}" placeholder="Nama lengkap pengemudi">
                        @error('nama_pengemudi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">No. SO/SA</label>
                    <div class="col-lg-9">
                        <input type="text" name="no_so_sa"
                            class="form-control @error('no_so_sa') is-invalid @enderror"
                            value="{{ old('no_so_sa') }}" placeholder="Contoh: SO-2025-001">
                        @error('no_so_sa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Jumlah (Liter)</label>
                    <div class="col-lg-9">
                        <input type="text" name="jumlah_liter"
                            class="form-control decimal-input @error('jumlah_liter') is-invalid @enderror"
                            value="{{ old('jumlah_liter') }}" placeholder="Contoh: 2.000,50" required>
                        <div class="form-text">Format: 2.000,50</div>
                        @error('jumlah_liter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Stok Awal (MM)</label>
                    <div class="col-lg-9">
                        <input type="text" name="stok_awal"
                            class="form-control decimal-input @error('stok_awal') is-invalid @enderror"
                            value="{{ old('stok_awal') }}" placeholder="Contoh: 90,50">
                        <div class="form-text">Stok awal tangki dalam milimeter (MM)</div>
                        @error('stok_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Stok Akhir (MM)</label>
                    <div class="col-lg-9">
                        <input type="text" name="stok_akhir"
                            class="form-control decimal-input @error('stok_akhir') is-invalid @enderror"
                            value="{{ old('stok_akhir') }}" placeholder="Contoh: 1.516,00">
                        <div class="form-text">Stok akhir tangki setelah pengisian dalam milimeter (MM)</div>
                        @error('stok_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Keterangan</label>
                    <div class="col-lg-9">
                        <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between py-6 px-0">
                    <a href="{{ route('tank-additions.index') }}" class="btn btn-light">
                        <i class="ki-outline ki-arrow-left fs-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decimalInputs = document.querySelectorAll('.decimal-input');
            decimalInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    let value = this.value.replace(/\./g, '');
                    let parts = value.split(',');
                    let mainNumber = parts[0];
                    let decimal = parts[1] || '';
                    mainNumber = mainNumber.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    if (decimal) {
                        decimal = decimal.substring(0, 2);
                        this.value = mainNumber + ',' + decimal;
                    } else {
                        this.value = mainNumber;
                    }
                });
                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    value = value.replace(/[^0-9.,]/g, '');
                    e.target.value = value;
                });
                input.addEventListener('focus', function() {
                    this.value = this.value.replace(/\./g, '');
                });
            });
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    decimalInputs.forEach(input => {
                        let value = input.value;
                        value = value.replace(/\./g, '');
                        value = value.replace(',', '.');
                        input.value = value;
                    });
                });
            }
        });
    </script>
@endpush
