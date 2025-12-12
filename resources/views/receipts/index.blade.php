@extends('layout.app')

@section('title', 'Cetak Struk')

@section('content')
    <div class="row g-5">
        <!-- Form Input -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Input Transaksi</h3>
                </div>
                <div class="card-body">
                    <form id="receiptForm">
                        <div class="mb-7">
                            <label class="form-label fw-bold">Tipe Input</label>
                            <div class="d-flex gap-5">
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="input_type" value="harga"
                                        id="input_harga" checked>
                                    <label class="form-check-label" for="input_harga">
                                        Input Harga
                                    </label>
                                </div>
                                <div class="form-check form-check-custom form-check-solid">
                                    <input class="form-check-input" type="radio" name="input_type" value="liter"
                                        id="input_liter">
                                    <label class="form-check-label" for="input_liter">
                                        Input Liter
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div id="harga_group" class="mb-7">
                            <label class="form-label required">Harga (Rp)</label>
                            <input type="number" id="input_harga_value" class="form-control form-control-lg"
                                placeholder="Masukkan harga" step="1" min="0">
                        </div>

                        <div id="liter_group" class="mb-7" style="display: none;">
                            <label class="form-label required">Liter</label>
                            <input type="number" id="input_liter_value" class="form-control form-control-lg"
                                placeholder="Masukkan liter" step="0.01" min="0">
                        </div>

                        <div class="separator my-10"></div>

                        <div class="mb-7">
                            <label class="form-label">Harga per Liter</label>
                            <div class="input-group input-group-solid">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control"
                                    value="{{ number_format($organization->harga_jual, 0, ',', '.') }}" readonly>
                            </div>
                        </div>

                        <div class="mb-7">
                            <label class="form-label">Total Liter</label>
                            <div class="fs-2hx fw-bold text-primary" id="result_liter">0</div>
                        </div>

                        <div class="mb-7">
                            <label class="form-label">Total Harga</label>
                            <div class="fs-2hx fw-bold text-success" id="result_harga">Rp 0</div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="button" class="btn btn-light btn-lg" onclick="resetForm()">
                                <i class="ki-outline ki-arrows-circle fs-4 me-1"></i>
                                Reset
                            </button>
                            <button type="button" class="btn btn-primary btn-lg" onclick="printReceipt()" id="printBtn"
                                disabled>
                                <i class="ki-outline ki-printer fs-4 me-1"></i>
                                Cetak Struk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Struk -->
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Preview Struk (58mm)</h3>
                </div>
                <div class="card-body d-flex justify-content-center">
                    <div id="receipt_preview"
                        style="width: 58mm; border: 1px solid #ddd; padding: 5mm; background: white; font-family: 'Courier New', monospace;">
                        <div style="text-align: center; margin-bottom: 3mm;">
                            <div style="font-size: 14px; font-weight: bold;">{{ $organization->name }}</div>
                            @if ($organization->alamat)
                                <div style="font-size: 10px;">{{ $organization->alamat }}</div>
                            @endif
                            @if ($organization->phone)
                                <div style="font-size: 10px;">Telp: {{ $organization->phone }}</div>
                            @endif
                            <div style="border-top: 1px dashed #000; margin: 2mm 0;"></div>
                        </div>

                        <div style="font-size: 10px; margin-bottom: 3mm;">
                            <div>No: <span id="preview_nomor">-</span></div>
                            <div>Tanggal: <span id="preview_tanggal">-</span></div>
                            <div style="border-top: 1px dashed #000; margin: 2mm 0;"></div>
                        </div>

                        <div style="font-size: 11px;">
                            <table style="width: 100%; margin-bottom: 3mm;">
                                <tr>
                                    <td>Harga/Liter</td>
                                    <td style="text-align: right;">Rp <span id="preview_harga_per_liter">0</span></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Liter</td>
                                    <td style="text-align: right; font-weight: bold;"><span id="preview_liter">0</span></td>
                                </tr>
                                <tr style="border-top: 1px dashed #000;">
                                    <td style="padding-top: 2mm; font-weight: bold; font-size: 13px;">TOTAL</td>
                                    <td style="text-align: right; padding-top: 2mm; font-weight: bold; font-size: 13px;">Rp
                                        <span id="preview_total">0</span></td>
                                </tr>
                            </table>
                        </div>

                        <div style="text-align: center; font-size: 10px; border-top: 1px dashed #000; padding-top: 2mm;">
                            <div>Terima Kasih</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentLiter = 0;
        let currentHarga = 0;
        const hargaPerLiter = {{ $organization->harga_jual }};

        // Toggle input type
        document.querySelectorAll('input[name="input_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'harga') {
                    document.getElementById('harga_group').style.display = 'block';
                    document.getElementById('liter_group').style.display = 'none';
                    document.getElementById('input_liter_value').value = '';
                } else {
                    document.getElementById('harga_group').style.display = 'none';
                    document.getElementById('liter_group').style.display = 'block';
                    document.getElementById('input_harga_value').value = '';
                }
                resetResults();
            });
        });

        // Input harga
        document.getElementById('input_harga_value').addEventListener('input', function() {
            const harga = parseFloat(this.value) || 0;
            if (harga > 0) {
                calculate('harga', harga);
            } else {
                resetResults();
            }
        });

        // Input liter
        document.getElementById('input_liter_value').addEventListener('input', function() {
            const liter = parseFloat(this.value) || 0;
            if (liter > 0) {
                calculate('liter', liter);
            } else {
                resetResults();
            }
        });

        function calculate(type, value) {
            fetch('{{ route('receipts.calculate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        input_type: type,
                        value: value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    currentLiter = data.liter;
                    currentHarga = data.harga;

                    document.getElementById('result_liter').textContent = formatNumber(data.liter, 2);
                    document.getElementById('result_harga').textContent = 'Rp ' + formatNumber(data.harga, 0);

                    updatePreview();
                    document.getElementById('printBtn').disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghitung');
                });
        }

        function updatePreview() {
            const now = new Date();
            const nomor = 'TRX-' + now.getFullYear() +
                String(now.getMonth() + 1).padStart(2, '0') +
                String(now.getDate()).padStart(2, '0') +
                String(now.getHours()).padStart(2, '0') +
                String(now.getMinutes()).padStart(2, '0') +
                String(now.getSeconds()).padStart(2, '0');

            const tanggal = String(now.getDate()).padStart(2, '0') + '/' +
                String(now.getMonth() + 1).padStart(2, '0') + '/' +
                now.getFullYear() + ' ' +
                String(now.getHours()).padStart(2, '0') + ':' +
                String(now.getMinutes()).padStart(2, '0');

            document.getElementById('preview_nomor').textContent = nomor;
            document.getElementById('preview_tanggal').textContent = tanggal;
            document.getElementById('preview_harga_per_liter').textContent = formatNumber(hargaPerLiter, 0);
            document.getElementById('preview_liter').textContent = formatNumber(currentLiter, 2);
            document.getElementById('preview_total').textContent = formatNumber(currentHarga, 0);
        }

        function resetForm() {
            document.getElementById('input_harga_value').value = '';
            document.getElementById('input_liter_value').value = '';
            document.getElementById('input_harga').checked = true;
            document.getElementById('harga_group').style.display = 'block';
            document.getElementById('liter_group').style.display = 'none';
            resetResults();
        }

        function resetResults() {
            currentLiter = 0;
            currentHarga = 0;
            document.getElementById('result_liter').textContent = '0';
            document.getElementById('result_harga').textContent = 'Rp 0';
            document.getElementById('preview_nomor').textContent = '-';
            document.getElementById('preview_tanggal').textContent = '-';
            document.getElementById('preview_liter').textContent = '0';
            document.getElementById('preview_total').textContent = '0';
            document.getElementById('printBtn').disabled = true;
        }

        function printReceipt() {
            if (currentLiter <= 0 || currentHarga <= 0) {
                alert('Silakan masukkan nilai terlebih dahulu');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('receipts.print') }}';
            form.target = '_blank';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const literInput = document.createElement('input');
            literInput.type = 'hidden';
            literInput.name = 'liter';
            literInput.value = currentLiter;
            form.appendChild(literInput);

            const hargaInput = document.createElement('input');
            hargaInput.type = 'hidden';
            hargaInput.name = 'harga';
            hargaInput.value = currentHarga;
            form.appendChild(hargaInput);

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }

        function formatNumber(num, decimal) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: decimal,
                maximumFractionDigits: decimal
            }).format(num);
        }
    </script>
@endpush
