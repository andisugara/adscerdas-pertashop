@extends('layout.app')

@section('title', 'Edit Operator')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Operator</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('operators.update', $operator) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-7">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $operator->name) }}" placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-7">
                    <label class="form-label required">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $operator->email) }}" placeholder="email@example.com" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Email akan digunakan untuk login</div>
                </div>

                <div class="mb-7">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="Kosongkan jika tidak ingin mengubah password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah password.</div>
                </div>

                <div class="mb-7">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="Ulangi password baru">
                </div>

                <div class="mb-7">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                            {{ old('is_active', $operator->pivot->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Aktif
                        </label>
                    </div>
                    <div class="form-text">Operator yang nonaktif tidak dapat login ke sistem</div>
                </div>

                <div class="separator my-10"></div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('operators.index') }}" class="btn btn-light me-3">
                        <i class="ki-outline ki-cross fs-4 me-1"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-4 me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
