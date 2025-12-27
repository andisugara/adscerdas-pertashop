@extends('layout.app')

@section('title', 'Detail Pengeluaran')

@section('content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pengeluaran</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold">Tanggal</label>
                        <div class="col-lg-8">
                            <span class="fw-bold">{{ $expense->tanggal->format('d/m/Y') }}</span>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold">Nama Pengeluaran</label>
                        <div class="col-lg-8">
                            <span class="fw-bold">{{ $expense->nama_pengeluaran }}</span>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold">Jumlah</label>
                        <div class="col-lg-8">
                            <span class="fw-bold text-success fs-5">{{ formatRupiah($expense->jumlah) }}</span>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold">Keterangan</label>
                        <div class="col-lg-8">
                            <span>{{ $expense->keterangan ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="row mb-6">
                        <label class="col-lg-4 col-form-label fw-semibold">Input By</label>
                        <div class="col-lg-8">
                            <span>{{ $expense->user->name }}</span>
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-lg-4 col-form-label fw-semibold">Tanggal Input</label>
                        <div class="col-lg-8">
                            <span>{{ $expense->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-between py-6 px-9">
                    <a href="{{ route('expenses.index') }}" class="btn btn-light">
                        <i class="ki-outline ki-arrow-left fs-2"></i> Kembali
                    </a>
                    <div>
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-primary">
                            <i class="ki-outline ki-pencil fs-2"></i> Edit
                        </a>
                        @if (auth()->user()->isOwner())
                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Yakin hapus pengeluaran ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="ki-outline ki-trash fs-2"></i> Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($expense->bukti_pengeluaran)
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bukti Pengeluaran</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal" class="cursor-pointer">
                                <img src="{{ Storage::url($expense->bukti_pengeluaran) }}" 
                                    alt="Bukti Pengeluaran" class="img-fluid rounded" style="max-height: 300px; cursor: pointer; transition: transform 0.2s;"
                                    onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ Storage::url($expense->bukti_pengeluaran) }}" 
                            class="btn btn-light-primary w-100" 
                            download="bukti-pengeluaran-{{ $expense->id }}.jpg">
                            <i class="ki-outline ki-download-2 fs-2"></i> Download
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal Image -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Bukti Pengeluaran - {{ $expense->nama_pengeluaran }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ Storage::url($expense->bukti_pengeluaran) }}" 
                                alt="Bukti Pengeluaran" class="img-fluid rounded">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            <a href="{{ Storage::url($expense->bukti_pengeluaran) }}" 
                                class="btn btn-primary" 
                                download="bukti-pengeluaran-{{ $expense->id }}.jpg">
                                <i class="ki-outline ki-download-2 fs-2"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
