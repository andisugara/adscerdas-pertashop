@extends('layout.app')

@section('title', 'Detail Setoran')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Detail Setoran</h3>
            <div class="card-toolbar">
                <a href="{{ route('deposits.edit', $deposit->id) }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-outline ki-pencil"></i> Edit
                </a>
                <a href="{{ route('deposits.index') }}" class="btn btn-sm btn-light">
                    <i class="ki-outline ki-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="fw-bold">Tanggal</div>
                        <div>{{ $deposit->tanggal->format('d/m/Y') }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold">Shift</div>
                        <div>{{ $deposit->shift->nama_shift }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold">Operator</div>
                        <div>{{ $deposit->user->name }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold">Jumlah</div>
                        <div>{{ formatRupiah($deposit->jumlah) }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="fw-bold">Keterangan</div>
                        <div>{{ $deposit->keterangan ?? '-' }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold mb-3">Bukti Setoran</div>
                    @if ($deposit->bukti_setoran)
                        <div class="border rounded p-3 text-center">
                            <img src="{{ asset('storage/' . $deposit->bukti_setoran) }}" alt="Bukti Setoran"
                                class="img-fluid rounded" style="cursor: pointer;" data-bs-toggle="modal"
                                data-bs-target="#buktiSetoranModal" />
                            <div class="mt-3">
                                <a href="{{ asset('storage/' . $deposit->bukti_setoran) }}" target="_blank"
                                    class="btn btn-light-info btn-sm">
                                    <i class="ki-outline ki-exit-down"></i> Buka di Tab Baru
                                </a>
                                <button type="button" class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#buktiSetoranModal">
                                    <i class="ki-outline ki-eye"></i> Perbesar
                                </button>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-600">Tidak ada bukti setoran.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($deposit->bukti_setoran)
        <div class="modal fade" id="buktiSetoranModal" tabindex="-1" aria-labelledby="buktiSetoranModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="buktiSetoranModalLabel">Bukti Setoran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ asset('storage/' . $deposit->bukti_setoran) }}" alt="Bukti Setoran" class="img-fluid rounded" />
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
