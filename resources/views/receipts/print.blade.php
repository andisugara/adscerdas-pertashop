<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Struk - {{ $nomor }}</title>
        <style>
            @page {
                size: 58mm auto;
                margin: 0;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                width: 58mm;
                font-family: 'Courier New', Courier, monospace;
                font-size: 10px;
                padding: 5mm;
                background: white;
            }

            .header {
                text-align: center;
                margin-bottom: 3mm;
                padding-bottom: 2mm;
                border-bottom: 1px dashed #000;
            }

            .header .name {
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 1mm;
            }

            .header .info {
                font-size: 9px;
                line-height: 1.3;
            }

            .transaction-info {
                margin-bottom: 3mm;
                padding-bottom: 2mm;
                border-bottom: 1px dashed #000;
                font-size: 9px;
            }

            .transaction-info div {
                margin-bottom: 1mm;
            }

            .items {
                margin-bottom: 3mm;
            }

            .items table {
                width: 100%;
                font-size: 10px;
                border-collapse: collapse;
            }

            .items table td {
                padding: 1mm 0;
            }

            .items table .label {
                width: 60%;
            }

            .items table .value {
                width: 40%;
                text-align: right;
            }

            .items table .bold-row td {
                font-weight: bold;
                font-size: 11px;
            }

            .total-row {
                border-top: 1px dashed #000;
                padding-top: 2mm;
            }

            .total-row .label {
                font-size: 13px;
                font-weight: bold;
            }

            .total-row .value {
                font-size: 13px;
                font-weight: bold;
            }

            .footer {
                text-align: center;
                margin-top: 3mm;
                padding-top: 2mm;
                border-top: 1px dashed #000;
                font-size: 9px;
            }

            @media print {
                body {
                    padding: 2mm;
                }

                .no-print {
                    display: none;
                }
            }

            @media screen {
                body {
                    margin: 20px auto;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <div class="name">{{ $organization->name }}</div>
            <div class="info">
                @if ($organization->alamat)
                    <div>{{ $organization->alamat }}</div>
                @endif
                @if ($organization->phone)
                    <div>Telp: {{ $organization->phone }}</div>
                @endif
            </div>
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div><strong>No Transaksi:</strong> {{ $nomor }}</div>
            <div><strong>Tanggal:</strong> {{ $tanggal->format('d/m/Y H:i') }}</div>
        </div>

        <!-- Items -->
        <div class="items">
            <table>
                <tr>
                    <td class="label">Harga/Liter</td>
                    <td class="value">Rp {{ number_format($harga_per_liter, 0, ',', '.') }}</td>
                </tr>
                <tr class="bold-row">
                    <td class="label">Liter</td>
                    <td class="value">{{ number_format($liter, 2, ',', '.') }}</td>
                </tr>
                <tr class="total-row">
                    <td class="label">TOTAL</td>
                    <td class="value">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>Terima Kasih</div>
            <div>Semoga Berkah</div>
        </div>

        <script>
            // Auto print when page loads
            window.onload = function() {
                window.print();
            }
        </script>
    </body>

</html>
