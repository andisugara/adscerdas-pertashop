# Pertashop SaaS Platform 🚀

Platform **SaaS Multi-Tenant** untuk manajemen SPBU (Stasiun Pengisian Bahan Bakar Umum) dengan sistem subscription, payment gateway, dan superadmin panel.

## 🎯 Overview

Pertashop adalah platform cloud-based yang memungkinkan **multiple pemilik SPBU** untuk:

-   Mengelola multiple pertashop dalam satu akun
-   Subscribe dengan trial 14 hari gratis
-   Bayar subscription via Manual Transfer atau Duitku
-   Manage operator dan shift secara terpisah per pertashop
-   Laporan harian otomatis dengan perhitungan margin & losses

## ✨ Key Features

### 🏢 Multi-Tenant Architecture

-   **1 Owner → Multiple Pertashops**: Satu owner bisa kelola banyak SPBU
-   **Data Isolation**: Setiap pertashop punya data terpisah
-   **Organization Switcher**: Ganti pertashop aktif di navbar
-   **Role-based Access**: Superadmin, Owner, Operator

### 💳 Subscription System

-   **Trial Period**: 14 hari gratis untuk pertashop baru
-   **Flexible Plans**: Monthly (Rp 100,000) atau Yearly (Rp 1,000,000)
-   **Dual Payment**: Manual transfer atau Duitku payment gateway
-   **Auto-Approval**: Duitku payments langsung aktif
-   **Manual Approval**: Upload bukti transfer → Superadmin approve

### 👨‍💼 Superadmin Panel

-   Dashboard dengan statistics
-   Manage semua organizations
-   Approve/reject subscriptions
-   View payment proofs
-   Configure system settings & pricing

### 📊 Operational Features

1. **Settings** → Harga BBM, Rumus, HPP, Nama & Alamat Pertashop
2. **Shift Management** → 2-3 shift per hari (Pagi/Siang/Malam)
3. **Daily Reports** → Totalisator, Stok, Margin, Losses per shift
4. **Tank Additions** → Input Delivery Order (DO)
5. **Expenses** → Pengeluaran operasional
6. **Deposits** → Setoran ke owner
7. **Salaries** → Gaji karyawan

### Installation

```bash
git clone <repository-url>
cd adscerdas-pertashop
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan storage:link
npm run build
php artisan serve
```

### Default Accounts

**Superadmin**:

-   Email: `superadmin@pertashop.com`
-   Password: `password`
-   Access: `/superadmin/dashboard`

**Demo Owner**:

-   Email: `owner@pertashop.com`
-   Password: `password`

**Demo Operator**:

-   Email: `operator@pertashop.com`
-   Password: `password`

## 📚 Documentation

-   **[DEPLOYMENT.md](DEPLOYMENT.md)** - Installation & deployment guide
-   **[DUITKU_INTEGRATION.md](DUITKU_INTEGRATION.md)** - Duitku payment setup
-   **[SUMMARY.md](SUMMARY.md)** - Complete feature summary & checklist

## 🏗️ Architecture

### Multi-Tenancy Pattern

-   **Shared Database** dengan `organization_id` discrimination
-   **Global Scope** untuk auto-filter queries
-   **Trait-based** assignment untuk DRY code
-   **Middleware** untuk subscription & access control

### Tech Stack

-   **Laravel 12.0** - Backend framework
-   **MySQL 8.0** - Database
-   **Tailwind CSS** - UI styling
-   **Guzzle HTTP** - Duitku API integration
-   **Vite** - Asset bundling

## 📊 Database Schema

### Core Tables

-   `organizations` - SPBU entities
-   `subscriptions` - Payment & subscription records
-   `organization_user` - Many-to-many with roles
-   `system_settings` - Global configuration
-   `users` - All users (superadmin, owners, operators)

### Operational Tables (per organization)

-   `settings` - BBM pricing & formulas
-   `shifts` - Shift configurations
-   `daily_reports` - Daily fuel reports per shift
-   `tank_additions` - Delivery orders (DO)
-   `expenses` - Operational costs
-   `deposits` - Owner deposits
-   `salaries` - Employee salaries

## 🔐 Security

-   **Data Isolation**: Organization-based filtering
-   **Role-based Access**: Superadmin/Owner/Operator
-   **Subscription Check**: Middleware validates access
-   **Trial Period**: Auto-expires after 14 days
-   **Payment Verification**: Manual approval or Duitku callback

## 🎯 User Workflows

### Owner Registration

1. Visit `/register/owner`
2. Fill personal + organization info
3. Choose Trial (14 days free)
4. Auto-login → Start using immediately

### Owner Subscription

1. Trial expires → Redirected to subscription page
2. Choose Monthly or Yearly plan
3. Select payment method:
    - **Manual**: Upload transfer proof → Wait approval
    - **Duitku**: Auto payment → Instant activation
4. Access granted after payment confirmed

### Superadmin Management

1. Login → `/superadmin/dashboard`
2. View pending subscriptions
3. Click subscription → View payment proof
4. Approve or Reject → User gets access immediately

## 📱 Features Detail

### Daily Report Calculation

```
TA = Totalisator Awal
TAK = Totalisator Akhir
SA = Stok Awal (MM)
SAK = Stok Akhir (MM)
DO = Delivery Order (Liter)

SAL = SA / Rumus (Stok Awal Liter)
SAKL = SAK / Rumus (Stok Akhir Liter)
Penjualan = TAK - TA
LL = (SAL + DO) - SAKL - Penjualan (Loses Liter)
LR = LL × HPP (Loses Rupiah)
M/H = Penjualan × (Harga Jual - HPP) (Margin per Hari)
```

### Organization Switcher

-   Dropdown di navbar menampilkan semua pertashop owner
-   Click organization → Switch context
-   Semua data auto-filtered ke pertashop aktif
-   Settings, shifts, reports terpisah per organization

## 🚀 Deployment

See **[DEPLOYMENT.md](DEPLOYMENT.md)** for complete production deployment guide.

### Quick Production Setup

1. Configure `.env` (database, mail, etc)
2. Run migrations & seeders
3. Change default passwords
4. Setup Duitku credentials (optional)
5. Configure SSL/HTTPS
6. Setup backups

## 🔧 Configuration

### System Settings (Superadmin)

Path: `/superadmin/settings`

-   Payment Gateway (Manual/Duitku)
-   Trial Days (default: 14)
-   Monthly Price (default: Rp 100,000)
-   Yearly Price (default: Rp 1,000,000)
-   Duitku Credentials

### Per-Organization Settings

Path: `/settings`

-   Harga Jual BBM
-   Rumus (density)
-   HPP per Liter
-   Nama Pertashop
-   Alamat

## 📞 Support

For issues, questions, or feature requests:

1. Check documentation files
2. Review logs: `storage/logs/laravel.log`
3. Contact development team

## 📄 License

Proprietary - All rights reserved

## 🎉 Version

**v2.0.0** - SaaS Multi-Tenant Platform  
Release Date: December 9, 2025

### Changelog

-   ✨ Multi-tenant architecture
-   ✨ Subscription management
-   ✨ Dual payment gateway
-   ✨ Superadmin panel
-   ✨ Organization switcher
-   ✨ Trial period system

---

**Built with ❤️ for Indonesian SPBU owners**

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
