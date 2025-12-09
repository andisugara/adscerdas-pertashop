@extends('layout.app')

@section('title', 'Input Laporan Harian')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Input Laporan Harian Tangki</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('daily-reports.store') }}" method="POST">
                @csrf

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Tanggal</label>
                    <div class="col-lg-9">
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Shift</label>
                    <div class="col-lg-9">
                        <select name="shift_id" class="form-select @error('shift_id') is-invalid @enderror" required>
                            <option value="">Pilih Shift</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->nama_shift }} ({{ date('H:i', strtotime($shift->jam_mulai)) }} -
                                    {{ date('H:i', strtotime($shift->jam_selesai)) }})
                                </option>
                            @endforeach
                        </select>
                        @error('shift_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="separator my-6"></div>
                <h4 class="mb-6">Data Totalisator</h4>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Totalisator Awal (TA)</label>
                    <div class="col-lg-9">
                        <input type="text" name="totalisator_awal"
                            class="form-control decimal-input @error('totalisator_awal') is-invalid @enderror"
                            value="{{ old('totalisator_awal') }}" placeholder="Contoh: 1.437.356,371" required>
                        <div class="form-text">Format: 1.437.356,371 (gunakan koma untuk desimal, maksimal 3 digit desimal)
                        </div>
                        @error('totalisator_awal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Totalisator Akhir (TAK)</label>
                    <div class="col-lg-9">
                        <input type="text" name="totalisator_akhir"
                            class="form-control decimal-input @error('totalisator_akhir') is-invalid @enderror"
                            value="{{ old('totalisator_akhir') }}" placeholder="Contoh: 1.437.500,500" required>
                        <div class="form-text">Format: 1.437.500,500 (gunakan koma untuk desimal, maksimal 3 digit desimal)
                        </div>
                        @error('totalisator_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="separator my-6"></div>
                <h4 class="mb-6">Data Stok Tangki</h4>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Stok Awal (MM)</label>
                    <div class="col-lg-9">
                        <input type="text" name="stok_awal_mm"
                            class="form-control decimal-input @error('stok_awal_mm') is-invalid @enderror"
                            value="{{ old('stok_awal_mm') }}" placeholder="Contoh: 1.200,50" required>
                        <div class="form-text">Stok dalam satuan MM (milimeter). Format: 1.200,50</div>
                        @error('stok_awal_mm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Stok Akhir (MM)</label>
                    <div class="col-lg-9">
                        <input type="text" name="stok_akhir_mm"
                            class="form-control decimal-input @error('stok_akhir_mm') is-invalid @enderror"
                            value="{{ old('stok_akhir_mm') }}" placeholder="Contoh: 1.150,75" required>
                        <div class="form-text">Stok dalam satuan MM (milimeter). Format: 1.150,75</div>
                        @error('stok_akhir_mm')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between py-6 px-0">
                    <a href="{{ route('daily-reports.index') }}" class="btn btn-light">
                        <i class="ki-outline ki-arrow-left fs-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-2"></i> Simpan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format input decimal dengan separator ribuan
            const decimalInputs = document.querySelectorAll('.decimal-input');

            decimalInputs.forEach(input => {
                // Format saat blur (keluar dari input)
                input.addEventListener('blur', function() {
                    formatDecimalInput(this);
                });

                // Format saat input (real-time)
                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    // Hapus semua karakter selain angka, titik, dan koma
                    value = value.replace(/[^0-9.,]/g, '');
                    e.target.value = value;
                });

                // Hilangkan format saat focus untuk memudahkan edit
                input.addEventListener('focus', function() {
                    let value = this.value;
                    // Hapus separator titik, biarkan koma desimal
                    this.value = value.replace(/\./g, '');
                });
            });

            function formatDecimalInput(input) {
                let value = input.value;

                // Hapus semua titik (separator ribuan lama)
                value = value.replace(/\./g, '');

                // Split antara angka utama dan desimal
                let parts = value.split(',');
                let mainNumber = parts[0];
                let decimal = parts[1] || '';

                // Tambahkan separator ribuan pada angka utama
                mainNumber = mainNumber.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

                // Gabungkan kembali
                if (decimal) {
                    // Batasi desimal maksimal 3 digit untuk totalisator, 2 untuk yang lain
                    const maxDecimal = input.name.includes('totalisator') ? 3 : 2;
                    decimal = decimal.substring(0, maxDecimal);
                    input.value = mainNumber + ',' + decimal;
                } else {
                    input.value = mainNumber;
                }
            }

            // Form akan dikirim dengan format Indonesia (1.234.567,89)
            // Controller parseDecimal() yang akan convert ke format database
        });
    </script>
@endpush
