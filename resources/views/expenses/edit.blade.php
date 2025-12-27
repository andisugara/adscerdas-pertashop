@extends('layout.app')

@section('title', 'Edit Pengeluaran')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Pengeluaran</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Tanggal</label>
                    <div class="col-lg-9">
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                            value="{{ old('tanggal', $expense->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Nama Pengeluaran</label>
                    <div class="col-lg-9">
                        <input type="text" name="nama_pengeluaran"
                            class="form-control @error('nama_pengeluaran') is-invalid @enderror"
                            value="{{ old('nama_pengeluaran', $expense->nama_pengeluaran) }}" required>
                        @error('nama_pengeluaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label required fw-semibold fs-6">Jumlah</label>
                    <div class="col-lg-9">
                        <input type="text" name="jumlah"
                            class="form-control decimal-input @error('jumlah') is-invalid @enderror"
                            value="{{ old('jumlah', number_format($expense->jumlah, 0, ',', '.')) }}" required>
                        <div class="form-text">Format: 50.000 atau 50.000,50</div>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Keterangan</label>
                    <div class="col-lg-9">
                        <textarea name="keterangan" rows="3" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $expense->keterangan) }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-6">
                    <label class="col-lg-3 col-form-label fw-semibold fs-6">Bukti Pengeluaran</label>
                    <div class="col-lg-9">
                        @if($expense->bukti_pengeluaran)
                            <div class="mb-3">
                                <div class="text-muted mb-2">Bukti saat ini:</div>
                                <div class="image-preview border rounded p-2" style="max-width: 300px;">
                                    <img src="{{ Storage::url($expense->bukti_pengeluaran) }}" alt="Bukti Pengeluaran" 
                                        class="img-fluid rounded" style="max-height: 250px;">
                                </div>
                                <label class="form-check form-check-custom form-check-solid mt-3">
                                    <input class="form-check-input" type="checkbox" name="delete_image" value="1">
                                    <span class="form-check-label fw-semibold">Hapus bukti ini</span>
                                </label>
                            </div>
                        @endif
                        
                        <div>
                            <label class="form-label fw-semibold">Upload bukti pengeluaran baru</label>
                            <input type="file" name="bukti_pengeluaran" accept="image/*"
                                class="form-control @error('bukti_pengeluaran') is-invalid @enderror" id="bukti_input">
                            <div class="form-text">Format: JPG, PNG, GIF (Max 2MB)</div>
                            <div id="preview" class="mt-3" style="display: none;">
                                <small class="text-muted">Preview:</small>
                                <img id="preview_img" src="" alt="Preview" class="img-fluid rounded" style="max-height: 250px; max-width: 300px;">
                            </div>
                            @error('bukti_pengeluaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-between py-6 px-0">
                    <a href="{{ route('expenses.index') }}" class="btn btn-light">
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

            // Image preview
            const buktiInput = document.getElementById('bukti_input');
            if (buktiInput) {
                buktiInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            document.getElementById('preview_img').src = event.target.result;
                            document.getElementById('preview').style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
@endpush
