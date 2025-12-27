@extends('layout.app')

@section('title', 'Detail Penambahan Tangki')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Detail Penambahan Tangki (DO)</h3>
        </div>
        <div class="card-body">
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Tanggal</label>
                <div class="col-lg-9">
                    <span class="fw-bold">{{ $tankAddition->tanggal->format('d/m/Y') }}</span>
                </div>
            </div>

            @if($tankAddition->no_polisi)
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">No. Polisi</label>
                <div class="col-lg-9">
                    <span>{{ $tankAddition->no_polisi }}</span>
                </div>
            </div>
            @endif

            @if($tankAddition->shipment_no)
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Shipment No.</label>
                <div class="col-lg-9">
                    <span>{{ $tankAddition->shipment_no }}</span>
                </div>
            </div>
            @endif

            @if($tankAddition->nama_pengemudi)
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Nama Pengemudi</label>
                <div class="col-lg-9">
                    <span>{{ $tankAddition->nama_pengemudi }}</span>
                </div>
            </div>
            @endif

            @if($tankAddition->no_so_sa)
            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">No. SO/SA</label>
                <div class="col-lg-9">
                    <span>{{ $tankAddition->no_so_sa }}</span>
                </div>
            </div>
            @endif

            <div class="separator separator-dashed my-6"></div>

            <div class="row mb-6">
                <label class="col-lg-3 col-form-label fw-semibold">Jumlah Pengisian</label>
                <div class="col-lg-9">
                    <span class="fw-bold text-primary fs-5">{{ formatNumber($tankAddition->jumlah_liter) }} Liter</span>
                </div>
            </div>

            @if($tankAddition->stok_awal && $tankAddition->stok_akhir)
                <div class="separator separator-dashed my-6"></div>
                
                <h4 class="mb-4">Perhitungan Stok Tangki</h4>

                <div class="row mb-4">
                    <label class="col-lg-3 col-form-label fw-semibold">Stok Awal (SA)</label>
                    <div class="col-lg-9">
                        <span>{{ formatNumber($tankAddition->stok_awal) }} MM</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-lg-3 col-form-label fw-semibold">Stok Awal Liter (SAL)</label>
                    <div class="col-lg-9">
                        <span class="text-info">{{ formatNumber($tankAddition->stok_awal_liter) }} Liter</span>
                        <small class="text-muted d-block">= SA × Rumus ({{ $tankAddition->getSetting()->rumus ?? '-' }})</small>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-lg-3 col-form-label fw-semibold">Jumlah Pengisian</label>
                    <div class="col-lg-9">
                        <span>{{ formatNumber($tankAddition->jumlah_liter) }} Liter</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-lg-3 col-form-label fw-semibold">Stok Akhir (SAK)</label>
                    <div class="col-lg-9">
                        <span>{{ formatNumber($tankAddition->stok_akhir) }} MM</span>
                    </div>
                </div>

                <div class="row mb-4">
                    <label class="col-lg-3 col-form-label fw-semibold">Stok Akhir Liter (SAKL)</label>
                    <div class="col-lg-9">
                        <span class="text-info">{{ formatNumber($tankAddition->stok_akhir_liter) }} Liter</span>
                        <small class="text-muted d-block">= SAK × Rumus ({{ $tankAddition->getSetting()->rumus ?? '-' }})</small>
                    </div>
                </div>

                <div class="separator separator-dashed my-6"></div>

                <div class="row mb-4">
                    <label class="col-lg-3 col-form-label fw-semibold fs-5">Loses</label>
                    <div class="col-lg-9">
                        <span class="fw-bold fs-4 {{ $tankAddition->loses < 0 ? 'text-danger' : ($tankAddition->loses > 0 ? 'text-warning' : 'text-success') }}">
                            {{ formatNumber($tankAddition->loses) }} Liter
                        </span>
                        <small class="text-muted d-block">= (SAL + Jumlah) - SAKL</small>
                        @if($tankAddition->loses < 0)
                            <span class="badge badge-light-danger mt-2">Kehilangan/Susut</span>
                        @elseif($tankAddition->loses > 0)
                            <span class="badge badge-light-warning mt-2">Kelebihan</span>
                        @else
                            <span class="badge badge-light-success mt-2">Normal</span>
                        @endif
                    </div>
                </div>
            @endif

            @if($tankAddition->keterangan)
            <div class="separator separator-dashed my-6"></div>
            
            <div class="row mb-4">
                <label class="col-lg-3 col-form-label fw-semibold">Keterangan</label>
                <div class="col-lg-9">
                    <span>{{ $tankAddition->keterangan }}</span>
                </div>
            </div>
            @endif

            <div class="separator separator-dashed my-6"></div>

            <div class="row mb-4">
                <label class="col-lg-3 col-form-label fw-semibold">Input By</label>
                <div class="col-lg-9">
                    <span>{{ $tankAddition->user->name }}</span>
                </div>
            </div>

            <div class="row">
                <label class="col-lg-3 col-form-label fw-semibold">Tanggal Input</label>
                <div class="col-lg-9">
                    <span>{{ $tankAddition->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between py-6 px-9">
            <a href="{{ route('tank-additions.index') }}" class="btn btn-light">
                <i class="ki-outline ki-arrow-left fs-2"></i> Kembali
            </a>
            <div>
                <a href="{{ route('tank-additions.edit', $tankAddition->id) }}" class="btn btn-primary">
                    <i class="ki-outline ki-pencil fs-2"></i> Edit
                </a>
                @if (auth()->user()->isOwner())
                    <form action="{{ route('tank-additions.destroy', $tankAddition->id) }}" method="POST"
                        class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
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
@endsection
