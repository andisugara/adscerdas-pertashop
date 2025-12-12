@extends('layout.app')

@section('title', 'Edit Pertashop')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Pertashop: {{ $organization->name }}</h3>
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

            <form action="{{ route('organizations.update', $organization) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Nama Pertashop</label>
                    <div class="col-lg-9">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $organization->name) }}" required>
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
                            value="{{ old('kode_pertashop', $organization->kode_pertashop) }}" required>
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
                            value="{{ old('phone', $organization->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Email</label>
                    <div class="col-lg-9">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $organization->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Alamat</label>
                    <div class="col-lg-9">
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $organization->address) }}</textarea>
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
                            value="{{ old('harga_jual', $organization->harga_jual) }}" required>
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
                            value="{{ old('rumus', $organization->rumus) }}" required>
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
                            value="{{ old('hpp_per_liter', $organization->hpp_per_liter) }}" required>
                        <div class="form-text">Harga Pokok Penjualan, Contoh: 11500</div>
                        @error('hpp_per_liter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between py-6 px-9">
                    <a href="{{ route('organizations.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-2"></i> Update Pertashop
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
