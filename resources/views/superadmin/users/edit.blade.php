@extends('superadmin.layout')

@section('title', 'Edit User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit User</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-7">
                    <label class="form-label required">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-7">
                    <label class="form-label required">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-7">
                    <label class="form-label required">Role</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator
                        </option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-7">
                    <label class="form-label">Organisasi Aktif</label>
                    <select name="active_organization_id"
                        class="form-select @error('active_organization_id') is-invalid @enderror">
                        <option value="">Pilih Organisasi</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}"
                                {{ old('active_organization_id', $user->active_organization_id) == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('active_organization_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-light me-3">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-4 me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
