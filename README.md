saya ingin membuat platform laporan pengeluaran dan pemasukan pertashop

user ada pengelolaan pengguna(untuk pekerja/operator shift shift an,shift bisa pagi dan siang atau pagi,siang,malam (2/3 shift perhari tergantung pertashop/owner)) dan saya sebagai owener

fitur
1.setting -> ada setting harga bbm /harga_jual (ex: 12000) dan rumus (2.09), HPP/Liter (ex:11500)
nama pertashop,kode pertashop,alamat
2.Rekap Harian tangki
-masing masing operator ketika masuk akan input totalisator awal,totalisator akhir,stok
totalisator = adalah jumlah penjualan bbm
stok = jumlah bbm di tangki
TA = totalisator awal
TAK = totalisator Akhir
SA = Stok Awal
SAL = Stok Awal Liter
SAK = Stok Akhir
SAKL = Stok Akhir Liter
LL = Loses Liter (jumlah kehilangan bbm,biasanya kan klo nambah ada penguapan)
LR = Loses Rupiah (Jumlah kehilangan bbm versi rupiah)
M/H = Margin / Hari

3.Penambahan Tangki
jika ada tambahan/pembelian BBM (Delivery Order),maka akan masuk ke Stok
DO = Delivery Order (Dalam Liter)

misal untuk report harian di owner:
pegawai a : shift pagi = TA = 1000,TAK = 900, SA = 1100 (MM), SAK = 500 (MM), DO = 2000
pegawai b : shift siang = TA = 900,TAK = 750, SA = 500 (MM), SAK = 162 (MM)

maka nanti akan ada laporan harian di owner jadi 1 row,namun ada chevron jika di klik muncul detail masing masing shift (list table tree datatable,dibawahnya detail dari masing masing pegawai)

list rownya bakal jadi gini :
TA = 1000
TAK = 750
SALES (Liter) = TA - TAK = 250
Rupiah = Sales _ Harga bbm (ex:12000) = 3000000
SA = 1100 (MM)
SAL = SA _ Rumus (ex:2.09) = 2299
SAK = 162 (MM)
SAKL = SAK _ Rumus (ex:2.09) = 338.58
DO = 2000
LL = Sales - ((SAL + DO) -SAKL)
LR = HPP (ex:11500) _ LL
M/H = ((Harga bbm - HPP) \* SALES) +LR

kemudian ada chevron untuk detail -> masing masing inputan operator termasuk penambahan tangki

4.Pengeluaran
Input tanggal,nama pengeluaran (untuk apa),dan jumlah
5.Setoran
masing masing operator akan input setoran harian
misal :
pegawai a : pagi = 11 juta
pegawai b : sore = 12 juta

nanti di halaman report setoran owner akan ada list row harian/pertanggal dan ada chevron untuk detail operator yang melakukan setor
Shift Pagi = 11 juta
shift sore = 12 Juta
Total = SP + SS = 23 Juta
Setor Keluar = Total - Pengeluaran (tanggal tsb)

6.Report & Dashboard
1.report bisa di filter bulanan,tahunan,harian
2.misal report bulanan
-HPP/liter bulan tsb
-HPP RP = TOTAL SALES (LITER) _ HPP/liter
-Sales = Total Sales (Liter)
-Liter = Total Liter terjual
-Loses Liter = total loses liter bulan itu
-Loses RP = dalam rupiah
-Margin Kotor = Total Sales (Liter) - HPP RP + Loses RP
-Operasional = Total Pengeluaran bulan tsb
-Zakat = Margin Kotor _ 2,5%
-Profit = Margin kotor - operasional - zakat - gaji (bulan tsb)
-Pembelian = total pengisian tangki bln tersebut (Liter)
-Penjualan = total penjualan tangki bln tersebut (Liter)
-Selisih penjualan = pembelian - penjualan
-stok awal
-stok akhir
-selsisih stok = stok akhir - stok awal
-sisa stok real = selisih stok
-sisa stok beli = selsisih penjualan
-loses = selsih stok - selisih penjualan
-rata rata penjualan/hari
