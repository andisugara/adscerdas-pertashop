<div class="row g-5 g-xl-10 mb-5">
    <div class="col-md-4">
        <div class="card card-flush h-100">
            <div class="card-body">
                <div class="fw-bold text-gray-600">Total Sales (Liter)</div>
                <div class="fs-2hx fw-bold text-primary">{{ formatNumber($sales, 3) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-flush h-100">
            <div class="card-body">
                <div class="fw-bold text-gray-600">Margin Kotor</div>
                <div class="fs-2hx fw-bold text-success">{{ formatRupiah($marginKotor) }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-flush h-100">
            <div class="card-body">
                <div class="fw-bold text-gray-600">Profit</div>
                <div class="fs-2hx fw-bold text-info">{{ formatRupiah($profit) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Laporan Bulanan - {{ date('F Y', strtotime($month)) }}</h3>
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
                        <td>HPP / Liter</td>
                        <td class="text-end">{{ formatRupiah($hppPerLiter) }}</td>
                    </tr>
                    <tr>
                        <td>HPP Rp</td>
                        <td class="text-end">{{ formatRupiah($hppRp) }}</td>
                    </tr>
                    <tr class="table-active">
                        <td><strong>Sales (Liter)</strong></td>
                        <td class="text-end"><strong>{{ formatNumber($sales, 3) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Loses Liter</td>
                        <td class="text-end">{{ formatNumber($losesLiter, 3) }}</td>
                    </tr>
                    <tr>
                        <td>Loses Rp</td>
                        <td class="text-end">{{ formatRupiah($losesRp) }}</td>
                    </tr>
                    <tr class="table-success">
                        <td><strong>Margin Kotor</strong></td>
                        <td class="text-end"><strong>{{ formatRupiah($marginKotor) }}</strong></td>
                    </tr>
                    <tr>
                        <td>Operasional</td>
                        <td class="text-end">{{ formatRupiah($operasional) }}</td>
                    </tr>
                    <tr>
                        <td>Zakat (2.5%)</td>
                        <td class="text-end">{{ formatRupiah($zakat) }}</td>
                    </tr>
                    <tr class="table-info">
                        <td><strong>Profit</strong></td>
                        <td class="text-end"><strong>{{ formatRupiah($profit) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Pembelian (Liter)</td>
                        <td class="text-end">{{ formatNumber($pembelian, 3) }}</td>
                    </tr>
                    <tr>
                        <td>Penjualan (Liter)</td>
                        <td class="text-end">{{ formatNumber($penjualan, 3) }}</td>
                    </tr>
                    <tr>
                        <td>Selisih Penjualan</td>
                        <td class="text-end">{{ formatNumber($selisihPenjualan, 3) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>Stok Awal (MM)</td>
                        <td class="text-end">{{ formatNumber($stokAwal) }}</td>
                    </tr>
                    <tr>
                        <td>Stok Akhir (MM)</td>
                        <td class="text-end">{{ formatNumber($stokAkhir) }}</td>
                    </tr>
                    <tr>
                        <td>Selisih Stok</td>
                        <td class="text-end">{{ formatNumber($selisihStok) }}</td>
                    </tr>
                    <tr>
                        <td>Sisa Stok Real</td>
                        <td class="text-end">{{ formatNumber($sisaStokReal) }}</td>
                    </tr>
                    <tr>
                        <td>Sisa Stok Beli</td>
                        <td class="text-end">{{ formatNumber($sisaStokBeli) }}</td>
                    </tr>
                    <tr class="table-warning">
                        <td><strong>Loses</strong></td>
                        <td class="text-end"><strong>{{ formatNumber($loses) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr class="table-primary">
                        <td><strong>Rata-rata Penjualan / Hari</strong></td>
                        <td class="text-end"><strong>{{ formatNumber($rataRataPenjualanPerHari, 3) }} Liter</strong>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
