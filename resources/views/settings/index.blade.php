@extends('layout.app')

@section('title', 'Pengaturan Pertashop')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Setting Pertashop</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('settings.update', $setting->id ?? 1) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Nama Pertashop</label>
                    <div class="col-lg-9">
                        <input type="text" name="nama_pertashop"
                            class="form-control @error('nama_pertashop') is-invalid @enderror"
                            value="{{ old('nama_pertashop', $setting->nama_pertashop ?? '') }}" required>
                        @error('nama_pertashop')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Kode Pertashop</label>
                    <div class="col-lg-9">
                        <input type="text" name="kode_pertashop"
                            class="form-control @error('kode_pertashop') is-invalid @enderror"
                            value="{{ old('kode_pertashop', $setting->kode_pertashop ?? '') }}" required>
                        @error('kode_pertashop')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Alamat</label>
                    <div class="col-lg-9">
                        <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $setting->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="separator my-6"></div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Harga Jual BBM (per Liter)</label>
                    <div class="col-lg-9">
                        <input type="number" step="0.01" name="harga_jual"
                            class="form-control @error('harga_jual') is-invalid @enderror"
                            value="{{ old('harga_jual', $setting->harga_jual ?? 12000) }}" required>
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
                            class="form-control @error('rumus') is-invalid @enderror"
                            value="{{ old('rumus', $setting->rumus ?? 2.09) }}" required>
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
                            value="{{ old('hpp_per_liter', $setting->hpp_per_liter ?? 11500) }}" required>
                        <div class="form-text">Harga Pokok Penjualan, Contoh: 11500</div>
                        @error('hpp_per_liter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-2"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
