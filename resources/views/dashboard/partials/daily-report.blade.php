<div class="row g-5 g-xl-10 mb-5">
    <!-- Summary Cards -->
    <div class="col-md-3">
        <div class="card card-flush h-100">
            <div class="card-body">
                <div class="fw-bold text-gray-600">Total Penjualan (Liter)</div>
                <div class="fs-2hx fw-bold text-gray-900">{{ formatNumber($salesLiter, 3) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-flush h-100">
            <div class="card-body">
                <div class="fw-bold text-gray-600">Total Penjualan (Rp)</div>
                <div class="fs-2hx fw-bold text-primary">{{ formatRupiah($salesRp) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-flush h-100">
            <div class="card-body">
                <div class="fw-bold text-gray-600">Margin Hari Ini</div>
                <div class="fs-2hx fw-bold text-success">{{ formatRupiah($marginHari) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-flush h-100">
            <div class="card-body">
                <div class="fw-bold text-gray-600">Setor Keluar</div>
                <div class="fs-2hx fw-bold text-info">{{ formatRupiah($setorKeluar) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Detail Laporan -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rekap Harian - {{ date('d F Y', strtotime($date)) }}</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-row-bordered">
                <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
                        <th>Keterangan</th>
                        <th class="text-end">Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Totalisator Awal (TA)</td>
                        <td class="text-end">{{ formatNumber($ta, 3) }}</td>
                    </tr>
                    <tr>
                        <td>Totalisator Akhir (TAK)</td>
                        <td class="text-end">{{ formatNumber($tak, 3) }}</td>
                    </tr>
                    <tr class="table-active">
                        <td><strong>SALES (Liter)</strong></td>
                        <td class="text-end"><strong>{{ formatNumber($salesLiter, 3) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Rupiah</td>
                        <td class="text-end">{{ formatRupiah($salesRp) }}</td>
                    </tr>
                    <tr>
                        <td>Stok Awal (SA) - MM</td>
                        <td class="text-end">{{ formatNumber($sa) }}</td>
                    </tr>
                    <tr>
                        <td>Stok Awal Liter (SAL)</td>
                        <td class="text-end">{{ formatNumber($sal) }}</td>
                    </tr>
                    <tr>
                        <td>Stok Akhir (SAK) - MM</td>
                        <td class="text-end">{{ formatNumber($sak) }}</td>
                    </tr>
                    <tr>
                        <td>Stok Akhir Liter (SAKL)</td>
                        <td class="text-end">{{ formatNumber($sakl) }}</td>
                    </tr>
                    <tr>
                        <td>Delivery Order (DO)</td>
                        <td class="text-end">{{ formatNumber($do) }}</td>
                    </tr>
                    <tr class="table-warning">
                        <td><strong>Loses Liter (LL)</strong></td>
                        <td class="text-end"><strong>{{ formatNumber($ll, 3) }}</strong></td>
                    </tr>
                    <tr class="table-warning">
                        <td><strong>Loses Rupiah (LR)</strong></td>
                        <td class="text-end"><strong>{{ formatRupiah($lr) }}</strong></td>
                    </tr>
                    <tr class="table-success">
                        <td><strong>Margin / Hari (M/H)</strong></td>
                        <td class="text-end"><strong>{{ formatRupiah($marginHari) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Detail Per Shift -->
        @if ($reports->count() > 0)
            <div class="mt-10">
                <h4 class="mb-5">Detail Per Shift</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800 bg-light">
                                <th>Shift</th>
                                <th>Operator</th>
                                <th class="text-end">TA</th>
                                <th class="text-end">TAK</th>
                                <th class="text-end">SA (MM)</th>
                                <th class="text-end">SAK (MM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td>{{ $report->shift->nama_shift }}</td>
                                    <td>{{ $report->user->name }}</td>
                                    <td class="text-end">{{ formatNumber($report->totalisator_awal, 3) }}</td>
                                    <td class="text-end">{{ formatNumber($report->totalisator_akhir, 3) }}</td>
                                    <td class="text-end">{{ formatNumber($report->stok_awal_mm) }}</td>
                                    <td class="text-end">{{ formatNumber($report->stok_akhir_mm) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Detail Setoran -->
        @if ($deposits->count() > 0)
            <div class="mt-10">
                <h4 class="mb-5">Detail Setoran</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="fw-bold fs-6 text-gray-800 bg-light">
                                <th>Shift</th>
                                <th>Operator</th>
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($deposits as $deposit)
                                <tr>
                                    <td>{{ $deposit->shift->nama_shift }}</td>
                                    <td>{{ $deposit->user->name }}</td>
                                    <td class="text-end">{{ formatRupiah($deposit->jumlah) }}</td>
                                </tr>
                            @endforeach
                            <tr class="table-active">
                                <td colspan="2"><strong>Total Setoran</strong></td>
                                <td class="text-end"><strong>{{ formatRupiah($totalDeposit) }}</strong></td>
                            </tr>
                            <tr>
                                <td colspan="2">Pengeluaran</td>
                                <td class="text-end">{{ formatRupiah($totalExpenses) }}</td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="2"><strong>Setor Keluar</strong></td>
                                <td class="text-end"><strong>{{ formatRupiah($setorKeluar) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
