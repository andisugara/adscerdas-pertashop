@extends('superadmin.layout')

@section('title', 'Organizations')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Semua Organisasi</h3>
        </div>
        <div class="card-body">
            <table id="kt_datatable_organizations" class="table table-striped table-row-bordered gy-5 gs-7">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Users</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($organizations as $org)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $org->name }}</span>
                                    <span class="text-gray-500 fs-7">{{ $org->slug }}</span>
                                </div>
                            </td>
                            <td>{{ $org->email }}</td>
                            <td>{{ $org->phone }}</td>
                            <td>{{ $org->users_count }}</td>
                            <td>
                                @if ($org->is_active)
                                    @if ($org->isInTrial())
                                        <span class="badge badge-light-warning">Trial</span>
                                    @elseif($org->hasActiveSubscription())
                                        <span class="badge badge-light-success">Active</span>
                                    @else
                                        <span class="badge badge-light-danger">Expired</span>
                                    @endif
                                @else
                                    <span class="badge badge-light-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.organizations.show', $org) }}"
                                    class="btn btn-sm btn-light-primary">
                                    <i class="ki-outline ki-eye fs-4"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#kt_datatable_organizations').DataTable({
                order: [
                    [0, 'asc']
                ],
                pageLength: 25,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endpush
