@extends('superadmin.layout')

@section('title', 'Detail User')

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
        <div class="card-header">
            <h3 class="card-title">Detail User</h3>
            <div class="card-toolbar">
                <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-light-primary me-2">
                    <i class="ki-outline ki-pencil fs-4 me-1"></i>
                    Edit
                </a>
                <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-light-danger">
                        <i class="ki-outline ki-trash fs-4 me-1"></i>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Nama Lengkap</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $user->name }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Email</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $user->email }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Role</label>
                <div class="col-lg-8">
                    <span class="badge badge-light-{{ $user->role == 'owner' ? 'primary' : 'info' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Status</label>
                <div class="col-lg-8">
                    @if ($user->is_active ?? true)
                        <span class="badge badge-light-success">Active</span>
                    @else
                        <span class="badge badge-light-danger">Inactive</span>
                    @endif
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Organisasi Aktif</label>
                <div class="col-lg-8">
                    @if ($user->organization)
                        <span class="fw-bold fs-6 text-gray-800">{{ $user->organization->name }}</span>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
            </div>

            @if ($user->role == 'owner' && $user->organizations->count() > 0)
                <div class="row mb-7">
                    <label class="col-lg-4 fw-bold text-muted">Semua Organisasi</label>
                    <div class="col-lg-8">
                        @foreach ($user->organizations as $org)
                            <span class="badge badge-light me-2 mb-2">{{ $org->name }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Terdaftar</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $user->created_at->format('d F Y, H:i') }}</span>
                </div>
            </div>

            <div class="row mb-7">
                <label class="col-lg-4 fw-bold text-muted">Terakhir Update</label>
                <div class="col-lg-8">
                    <span class="fw-bold fs-6 text-gray-800">{{ $user->updated_at->format('d F Y, H:i') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
