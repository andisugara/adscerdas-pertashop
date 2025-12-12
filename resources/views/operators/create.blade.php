@extends('layout.app')

@section('title', 'Tambah Operator')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Operator Baru</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('operators.store') }}" method="POST">
                @csrf

                <div class="mb-7">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-7">
                    <label class="form-label required">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="email@example.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Email akan digunakan untuk login</div>
                </div>

                <div class="mb-7">
                    <label class="form-label required">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-7">
                    <label class="form-label required">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password"
                        required>
                </div>

                <div class="separator my-10"></div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('operators.index') }}" class="btn btn-light me-3">
                        <i class="ki-outline ki-cross fs-4 me-1"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-4 me-1"></i>
                        Simpan Operator
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
