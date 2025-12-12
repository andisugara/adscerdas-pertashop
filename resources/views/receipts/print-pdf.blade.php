<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Struk - {{ $nomor }}</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'DejaVu Sans', sans-serif;
                font-size: 9px;
                padding: 5px;
                line-height: 1.3;
            }

            .header {
                text-align: center;
                margin-bottom: 8px;
                border-bottom: 1px dashed #000;
                padding-bottom: 5px;
            }

            .header h1 {
                font-size: 12px;
                font-weight: bold;
                margin-bottom: 3px;
                text-transform: uppercase;
            }

            .header div {
                font-size: 8px;
                margin-bottom: 1px;
            }

            .transaction-info {
                margin: 8px 0;
                font-size: 8px;
            }

            .transaction-info div {
                margin-bottom: 2px;
            }

            .transaction-info strong {
                display: inline-block;
                width: 60px;
            }

            .items {
                margin: 8px 0;
                border-top: 1px dashed #000;
                border-bottom: 1px dashed #000;
                padding: 5px 0;
            }

            .items table {
                width: 100%;
                border-collapse: collapse;
            }

            .items td {
                padding: 3px 0;
                font-size: 9px;
            }

            .items .item-name {
                font-weight: bold;
            }

            .items .item-details {
                padding-left: 10px;
                font-size: 8px;
            }

            .items .price {
                text-align: right;
            }

            .total {
                margin: 8px 0;
                padding: 5px 0;
                border-top: 1px solid #000;
            }

            .total table {
                width: 100%;
            }

            .total td {
                padding: 2px 0;
                font-size: 10px;
            }

            .total .label {
                font-weight: bold;
            }

            .total .amount {
                text-align: right;
                font-weight: bold;
                font-size: 11px;
            }

            .footer {
                text-align: center;
                margin-top: 10px;
                padding-top: 5px;
                border-top: 1px dashed #000;
                font-size: 8px;
            }

            .footer div {
                margin-bottom: 2px;
            }
        </style>
    </head>

    <body>
        <!-- Header -->
        <div class="header">
            <h1>{{ $organization->name }}</h1>
            @if ($organization->address)
                <div>{{ $organization->address }}</div>
            @endif
            @if ($organization->phone)
                <div>Telp: {{ $organization->phone }}</div>
            @endif
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
                    <td class="item-name">Pertamax</td>
                    <td class="price">{{ number_format($harga, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td class="item-details" colspan="2">
                        {{ number_format($liter, 2, ',', '.') }} Liter x Rp
                        {{ number_format($harga_per_liter, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
        </div>

        <!-- Total -->
        <div class="total">
            <table>
                <tr>
                    <td class="label">TOTAL</td>
                    <td class="amount">Rp {{ number_format($harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>*** TERIMA KASIH ***</div>
            <div>Selamat Jalan dan Hati-Hati</div>
            <div style="margin-top: 5px; font-size: 7px;">Dicetak: {{ now()->format('d/m/Y H:i:s') }}</div>
        </div>
    </body>

</html>
