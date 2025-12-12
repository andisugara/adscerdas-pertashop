@extends('superadmin.layout')

@section('title', 'Manajemen User')

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

    <!-- Filter Card -->
    <div class="card mb-5">
        <div class="card-body">
            <form method="GET" action="{{ route('superadmin.users.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold">Cari User</label>
                    <input type="text" name="search" class="form-control" placeholder="Nama atau email..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="owner" {{ request('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                        <option value="operator" {{ request('role') == 'operator' ? 'selected' : '' }}>Operator</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">Organisasi</label>
                    <select name="organization_id" class="form-select">
                        <option value="">Semua Organisasi</option>
                        @foreach ($organizations as $org)
                            <option value="{{ $org->id }}"
                                {{ request('organization_id') == $org->id ? 'selected' : '' }}>
                                {{ $org->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-filter fs-4 me-1"></i>
                        Filter
                    </button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-light">
                        <i class="ki-outline ki-cross fs-4 me-1"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">Daftar User</span>
                <span class="text-muted mt-1 fw-semibold fs-7">{{ $users->total() }} user terdaftar</span>
            </h3>
        </div>
        <div class="card-body py-3">
            <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-100 align-middle gs-0 gy-3">
                    <thead>
                        <tr class="fw-bold text-muted">
                            <th class="min-w-200px">User</th>
                            <th class="min-w-150px">Email</th>
                            <th class="min-w-100px">Role</th>
                            <th class="min-w-150px">Organisasi</th>
                            <th class="min-w-100px">Status</th>
                            <th class="min-w-120px">Terdaftar</th>
                            <th class="min-w-100px text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="symbol symbol-45px me-5">
                                            <span class="symbol-label bg-light-primary text-primary fw-bold">
                                                {{ substr($user->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bold mb-1 fs-6">{{ $user->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted fw-semibold d-block">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-light-{{ $user->role == 'owner' ? 'primary' : 'info' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($user->organization)
                                        <span class="text-dark fw-semibold d-block">{{ $user->organization->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($user->is_active ?? true)
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="text-muted fw-semibold d-block">{{ $user->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('superadmin.users.show', $user) }}"
                                        class="btn btn-icon btn-light-primary btn-sm me-1">
                                        <i class="ki-outline ki-eye fs-4"></i>
                                    </a>
                                    <a href="{{ route('superadmin.users.edit', $user) }}"
                                        class="btn btn-icon btn-light-warning btn-sm">
                                        <i class="ki-outline ki-pencil fs-4"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Tidak ada data user</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
