@extends('superadmin.layout')

@section('title', 'Subscriptions')

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
            <h3 class="card-title">Daftar Subscription</h3>
        </div>
        <div class="card-body">
            <table id="kt_datatable_subscriptions" class="table table-striped table-row-bordered gy-5 gs-7">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th>Organisasi</th>
                        <th>Paket</th>
                        <th>Harga</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subscriptions as $sub)
                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800 fw-bold">{{ $sub->organization->name }}</span>
                                </div>
                            </td>
                            <td>{{ ucfirst($sub->plan_name) }}</td>
                            <td>Rp {{ number_format($sub->price, 0, ',', '.') }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-gray-800">{{ $sub->starts_at->format('d M Y') }}</span>
                                    <span class="text-gray-500 fs-7">s/d {{ $sub->ends_at->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td>
                                @if ($sub->status === 'active')
                                    <span class="badge badge-light-success">Active</span>
                                @elseif($sub->status === 'pending')
                                    <span class="badge badge-light-warning">Pending</span>
                                @else
                                    <span class="badge badge-light-secondary">{{ ucfirst($sub->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($sub->payment_status === 'paid')
                                    <span class="badge badge-light-success">Paid</span>
                                @elseif($sub->payment_status === 'pending')
                                    <span class="badge badge-light-warning">Pending</span>
                                @else
                                    <span class="badge badge-light-danger">{{ ucfirst($sub->payment_status) }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('superadmin.subscriptions.show', $sub) }}"
                                    class="btn btn-sm btn-light-primary">
                                    <i class="ki-outline ki-eye fs-4"></i> View
                                </a>
                                @if ($sub->status === 'pending')
                                    <form action="{{ route('superadmin.subscriptions.approve', $sub) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light-success"
                                            onclick="return confirm('Setujui subscription ini?')">
                                            <i class="ki-outline ki-check fs-4"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('superadmin.subscriptions.reject', $sub) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-light-danger"
                                            onclick="return confirm('Tolak subscription ini?')">
                                            <i class="ki-outline ki-cross fs-4"></i> Reject
                                        </button>
                                    </form>
                                @endif
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
            $('#kt_datatable_subscriptions').DataTable({
                order: [
                    [3, 'desc']
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
