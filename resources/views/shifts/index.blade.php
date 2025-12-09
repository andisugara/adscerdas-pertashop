@extends('layout.app')

@section('title', 'Manajemen Shift')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Shift</h3>
            <div class="card-toolbar">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalShift">
                    <i class="ki-outline ki-plus fs-2"></i> Tambah Shift
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-row-bordered align-middle gy-4">
                    <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th>Nama Shift</th>
                            <th>Jam Mulai</th>
                            <th>Jam Selesai</th>
                            <th>Urutan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($shifts as $shift)
                            <tr>
                                <td>{{ $shift->nama_shift }}</td>
                                <td>{{ date('H:i', strtotime($shift->jam_mulai)) }}</td>
                                <td>{{ date('H:i', strtotime($shift->jam_selesai)) }}</td>
                                <td>{{ $shift->urutan }}</td>
                                <td>
                                    @if ($shift->aktif)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-light-primary"
                                        onclick="editShift({{ $shift->id }}, '{{ $shift->nama_shift }}', '{{ date('H:i', strtotime($shift->jam_mulai)) }}', '{{ date('H:i', strtotime($shift->jam_selesai)) }}', {{ $shift->urutan }}, {{ $shift->aktif ? 'true' : 'false' }})">
                                        <i class="ki-outline ki-pencil fs-5"></i> Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-600">Tidak ada data shift</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit Shift -->
    <div class="modal fade" id="modalShift" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formShift" method="POST" action="{{ route('shifts.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="methodShift" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalShiftTitle">Tambah Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-5">
                            <label class="form-label required">Nama Shift</label>
                            <input type="text" name="nama_shift" id="nama_shift" class="form-control" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required">Jam Mulai</label>
                            <input type="time" name="jam_mulai" id="jam_mulai" class="form-control" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required">Jam Selesai</label>
                            <input type="time" name="jam_selesai" id="jam_selesai" class="form-control" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label required">Urutan</label>
                            <input type="number" name="urutan" id="urutan" class="form-control" min="1"
                                required>
                        </div>

                        <div class="mb-5">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="aktif" id="aktif" value="1"
                                    checked>
                                <label class="form-check-label" for="aktif">Aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function editShift(id, nama, jamMulai, jamSelesai, urutan, aktif) {
            document.getElementById('modalShiftTitle').textContent = 'Edit Shift';
            document.getElementById('formShift').action = '/shifts/' + id;
            document.getElementById('methodShift').value = 'PUT';
            document.getElementById('nama_shift').value = nama;
            document.getElementById('jam_mulai').value = jamMulai;
            document.getElementById('jam_selesai').value = jamSelesai;
            document.getElementById('urutan').value = urutan;
            document.getElementById('aktif').checked = aktif;

            new bootstrap.Modal(document.getElementById('modalShift')).show();
        }

        document.getElementById('modalShift').addEventListener('hidden.bs.modal', function() {
            document.getElementById('modalShiftTitle').textContent = 'Tambah Shift';
            document.getElementById('formShift').action = '{{ route('shifts.store') }}';
            document.getElementById('methodShift').value = 'POST';
            document.getElementById('formShift').reset();
            document.getElementById('aktif').checked = true;
        });
    </script>
@endpush
