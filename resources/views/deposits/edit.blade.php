@extends('layout.app')

@section('title', 'Edit Setoran')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Setoran</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('deposits.update', $deposit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Tanggal</label>
                    <div class="col-lg-9">
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', $deposit->tanggal->format('Y-m-d')) }}" required>
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
                                <option value="{{ $shift->id }}"
                                    {{ old('shift_id', $deposit->shift_id) == $shift->id ? 'selected' : '' }}>
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

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Jumlah Setoran</label>
                    <div class="col-lg-9">
                        <input type="text" name="jumlah"
                            class="form-control decimal-input @error('jumlah') is-invalid @enderror"
                            value="{{ old('jumlah', number_format($deposit->jumlah, 0, ',', '.')) }}" required>
                        <div class="form-text">Format: 1.000.000</div>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Keterangan</label>
                    <div class="col-lg-9">
                        <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $deposit->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between py-6 px-0">
                    <a href="{{ route('deposits.index') }}" class="btn btn-light">
                        <i class="ki-outline ki-arrow-left fs-2"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ki-outline ki-check fs-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const decimalInputs = document.querySelectorAll('.decimal-input');
            decimalInputs.forEach(input => {
                input.addEventListener('blur', function() {
                    let value = this.value.replace(/\./g, '');
                    let parts = value.split(',');
                    let mainNumber = parts[0];
                    let decimal = parts[1] || '';
                    mainNumber = mainNumber.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                    if (decimal) {
                        decimal = decimal.substring(0, 2);
                        this.value = mainNumber + ',' + decimal;
                    } else {
                        this.value = mainNumber;
                    }
                });
                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    value = value.replace(/[^0-9.,]/g, '');
                    e.target.value = value;
                });
                input.addEventListener('focus', function() {
                    this.value = this.value.replace(/\./g, '');
                });
            });
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    decimalInputs.forEach(input => {
                        let value = input.value;
                        value = value.replace(/\./g, '');
                        value = value.replace(',', '.');
                        input.value = value;
                    });
                });
            }
        });
    </script>
@endpush
