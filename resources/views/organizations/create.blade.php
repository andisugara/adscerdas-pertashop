@extends('layout.app')

@section('title', 'Tambah Pertashop')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Pertashop Baru</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="ki-outline ki-information fs-2 me-2"></i>Terjadi Kesalahan!</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('organizations.store') }}" method="POST">
                @csrf

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Nama Pertashop</label>
                    <div class="col-lg-9">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Kode Pertashop</label>
                    <div class="col-lg-9">
                        <input type="text" name="kode_pertashop"
                            class="form-control @error('kode_pertashop') is-invalid @enderror"
                            value="{{ old('kode_pertashop') }}" required>
                        <div class="form-text">Contoh: PMJ001, SPBU123</div>
                        @error('kode_pertashop')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Telepon</label>
                    <div class="col-lg-9">
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Email</label>
                    <div class="col-lg-9">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Alamat</label>
                    <div class="col-lg-9">
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="separator my-6"></div>

                <h4 class="mb-5">Pengaturan Harga</h4>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Harga Jual BBM (per Liter)</label>
                    <div class="col-lg-9">
                        <input type="number" step="0.01" name="harga_jual"
                            class="form-control @error('harga_jual') is-invalid @enderror"
                            value="{{ old('harga_jual', 12000) }}" required>
                        <div class="form-text">Contoh: 12000</div>
                        @error('harga_jual')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Rumus Konversi MM ke Liter</label>
                    <div class="col-lg-9">
                        <input type="number" step="0.01" name="rumus"
                            class="form-control @error('rumus') is-invalid @enderror" value="{{ old('rumus', 2.09) }}"
                            required>
                        <div class="form-text">Contoh: 2.09</div>
                        @error('rumus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">HPP per Liter</label>
                    <div class="col-lg-9">
                        <input type="number" step="0.01" name="hpp_per_liter"
                            class="form-control @error('hpp_per_liter') is-invalid @enderror"
                            value="{{ old('hpp_per_liter', 11500) }}" required>
                        <div class="form-text">Harga Pokok Penjualan, Contoh: 11500</div>
                        @error('hpp_per_liter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="separator my-6"></div>

                <h4 class="mb-5">Data Awal Pertashop</h4>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Stok Awal (Liter)</label>
                    <div class="col-lg-9">
                        <input type="text" name="stok_awal"
                            class="form-control decimal-input @error('stok_awal') is-invalid @enderror"
                            value="{{ old('stok_awal', '0') }}" required placeholder="Contoh: 1500.750">
                        <div class="form-text">Stok BBM awal. Gunakan titik (.) untuk desimal (3 digit). Contoh: 1500.750
                        </div>
                        @error('stok_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Totalisator Awal (Liter)</label>
                    <div class="col-lg-9">
                        <input type="text" name="totalisator_awal"
                            class="form-control decimal-input @error('totalisator_awal') is-invalid @enderror"
                            value="{{ old('totalisator_awal', '0') }}" required placeholder="Contoh: 5000.250">
                        <div class="form-text">Angka totalisator (total penjualan). Gunakan titik (.) untuk desimal (3
                            digit). Contoh: 5000.250</div>
                        @error('totalisator_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between py-6 px-9">
                    <a href="{{ route('organizations.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-2"></i> Simpan Pertashop
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-convert comma to dot for decimal inputs
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.decimal-input').forEach(input => {
                input.addEventListener('blur', function() {
                    // Replace comma with dot
                    this.value = this.value.replace(/,/g, '.');

                    // Remove multiple dots, keep only first one
                    const parts = this.value.split('.');
                    if (parts.length > 2) {
                        this.value = parts[0] + '.' + parts.slice(1).join('');
                    }

                    // Ensure valid number
                    if (this.value && !isNaN(this.value)) {
                        this.value = parseFloat(this.value).toString();
                    }
                });

                // Only allow numbers, dot, and comma
                input.addEventListener('keypress', function(e) {
                    const char = String.fromCharCode(e.which);
                    if (!/[\d.,]/.test(char)) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
@endpush
