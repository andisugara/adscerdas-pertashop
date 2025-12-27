# Copilot Instructions - Pertashop SaaS Platform

**AI Model**: Claude Haiku 4.5 (Enable for all clients)

---

## 📋 Project Summary

**Pertashop** adalah platform **SaaS Multi-Tenant** untuk manajemen SPBU (Stasiun Pengisian Bahan Bakar Umum) dengan sistem subscription dan payment gateway terintegrasi.

### 🎯 Tujuan Utama
- Memungkinkan **multiple pemilik SPBU** mengelola pertashop mereka melalui satu platform cloud
- Sistem subscription dengan trial 14 hari gratis
- Multi-payment: Manual Transfer dan Duitku Payment Gateway
- Manajemen operasional: shift, laporan harian, tank additions, expenses, deposits, dan salaries

---

## 🏗️ Arsitektur Teknis

### Tech Stack
- **Backend**: Laravel 12.0 (PHP 8.2+)
- **Frontend**: Vue.js / Blade Templates
- **Database**: MySQL/PostgreSQL
- **Payment Gateway**: Duitku (+ Manual Transfer)
- **Testing**: Pest PHP 4.1
- **Code Quality**: Laravel Pint

### Multi-Tenancy Implementation
- **Pattern**: Shared Database dengan `organization_id` discrimination
- **Scope**: Global scope untuk auto-filter queries per tenant
- **Access Control**: Middleware untuk subscription validation & role-based access
- **Data Isolation**: Setiap pertashop punya data terpisah & terisolasi

### Database Models
- `User` - Users dengan role (superadmin, owner, operator)
- `Organization` - Pertashop instances
- `Subscription` - Subscription plans & status
- `Setting` - Per-organization settings (harga BBM, rumus, HPP)
- `Shift` - Shift management (Pagi/Siang/Malam)
- `DailyReport` - Laporan harian dengan totalisator, stok, margin
- `TankAddition` - Input Delivery Order (DO)
- `Expense` - Pengeluaran operasional
- `Deposit` - Setoran ke owner
- `Salary` - Gaji karyawan
- `SystemSetting` - Global settings untuk superadmin

---

## 🔑 Key Features

### 1. Multi-Tenant Organization
- Satu owner dapat mengelola multiple pertashop
- Organization switcher di navbar untuk ganti pertashop aktif
- Setiap pertashop memiliki data & configuration terpisah

### 2. Subscription System
- **Trial Period**: 14 hari gratis untuk pertashop baru
- **Plans**: Monthly (Rp 100,000) & Yearly (Rp 1,000,000)
- **Payment Methods**:
  - Manual Transfer (require approval dari superadmin)
  - Duitku (auto-approval upon payment confirmation)

### 3. Superadmin Panel
- Dashboard dengan statistics lengkap
- Manage semua organizations
- Approve/reject subscriptions
- View & validate payment proofs
- Configure system settings & pricing globally

### 4. Operational Features
- **Settings**: Harga BBM, rumus perhitungan, HPP, info pertashop
- **Shift Management**: Multiple shifts per hari
- **Daily Reports**: Perhitungan otomatis margin & losses
- **Tank Additions**: Tracking delivery orders (DO)
- **Expenses**: Pengeluaran operasional per shift
- **Deposits**: Setoran ke owner dengan tracking
- **Salaries**: Manajemen gaji karyawan

---

## 📁 Project Structure

```
app/
├── Http/Controllers/        # Business logic untuk setiap fitur
├── Models/                  # Eloquent models dengan scopes & traits
│   └── Scopes/             # Global scopes untuk multi-tenancy
│   └── Traits/             # Traits untuk reusable functionality
├── Helpers/                 # Helper functions
└── Providers/              # Service providers

routes/
├── web.php                 # Public & authenticated routes
├── auth.php                # Authentication routes
├── superadmin.php          # Superadmin panel routes
└── console.php             # Artisan commands

database/
├── migrations/             # Database schema changes
├── factories/              # Model factories untuk testing
└── seeders/                # Database seeders

resources/
├── views/                  # Blade templates
├── css/                    # Tailwind CSS
└── js/                     # Vue.js components / Alpine.js

tests/
├── Feature/                # Feature tests dengan Pest
└── Unit/                   # Unit tests
```

---

## 🔐 Authentication & Authorization

### Roles
1. **Superadmin**: Akses penuh ke seluruh platform
   - Manage organizations
   - Approve subscriptions & payments
   - System configuration
   - Access: `/superadmin/dashboard`

2. **Owner**: Pemilik pertashop
   - Manage 1 atau lebih pertashops
   - Set settings, manage operators
   - View laporan dan financial data

3. **Operator**: Karyawan pertashop
   - Input daily reports per shift
   - Manage tank additions & expenses
   - View limited operational data

### Default Accounts (Development)
```
Superadmin:
  Email: superadmin@pertashop.com
  Password: password

Owner:
  Email: owner@pertashop.com
  Password: password

Operator:
  Email: operator@pertashop.com
  Password: password
```

---

## 💾 Database Conventions

### Multi-Tenancy Queries
Semua model yang berhubungan dengan tenant harus menggunakan `organization_id`:

```php
// Automatic filtering via global scope
$reports = DailyReport::all(); // Hanya untuk org_id user saat ini

// Raw queries harus mempertimbangkan organization_id
User::where('organization_id', auth()->user()->organization_id)->get();
```

### Naming Conventions
- Foreign keys: `{model}_id` (e.g., `organization_id`, `shift_id`)
- Timestamps: `created_at`, `updated_at` (automatic)
- Soft deletes: `deleted_at` (jika digunakan)
- Status fields: `status` dengan enum values

---

## 🧪 Testing Strategy

### Tools
- **Testing Framework**: Pest PHP 4.1
- **Mocking**: Mockery
- **Factories**: Faker untuk test data generation

### Test Locations
- Feature tests: `tests/Feature/` - Test user workflows & API endpoints
- Unit tests: `tests/Unit/` - Test individual functions & logic

### Example Testing Patterns
```php
// Feature test for subscription approval
test('superadmin can approve subscription', function () {
    $subscription = Subscription::factory()->pending()->create();
    
    actingAs(User::factory()->superadmin()->create())
        ->post('/superadmin/subscriptions/' . $subscription->id . '/approve')
        ->assertRedirect();
    
    expect($subscription->refresh()->status)->toBe('active');
});
```

---

## 🚀 Development Guidelines

### Code Standards
- **PHP**: PSR-12 (via Laravel Pint)
- **Database**: Follow naming conventions (snake_case)
- **API**: RESTful endpoints with proper HTTP methods
- **Validation**: Use Form Request Classes or inline validators

### Multi-Tenancy Best Practices
1. **Always filter by organization**: Setiap query harus mempertimbangkan `organization_id`
2. **Use global scopes**: Implement `addGlobalScope()` untuk automatic filtering
3. **Validate ownership**: Verify user owns the resource sebelum update/delete
4. **Middleware protection**: Protect routes dengan subscription check middleware

### Git Workflow
- Feature branches: `feature/description`
- Bugfix branches: `bugfix/description`
- Follow conventional commits untuk clarity

### Code Review Checklist
- ✅ Multi-tenancy data isolation verified
- ✅ Tests written & passing
- ✅ Database migrations reversible
- ✅ Error handling & validation complete
- ✅ Code follows PSR-12 standards
- ✅ No hardcoded values (use config/env)

---

## 📊 Key Formulas & Calculations

### Daily Report Calculations
```
Stok Awal = Previous day's Stok Akhir
Pembelian = Sum of TankAddition quantities
Stok Terpakai = Totalisator (from pump)
Stok Akhir = Stok Awal + Pembelian - Stok Terpakai

Margin = (Harga Jual - HPP) × Stok Terpakai
Losses = Stok Terpakai (calculated) - Totalisator (measured)
```

### Subscription Expiry
- Trial: 14 days from organization creation
- Paid: Based on plan type (Monthly/Yearly)
- Grace Period: Auto-disable after 3 days expired

---

## 🔗 Related Documentation

- `DEPLOYMENT.md` - Installation & deployment procedures
- `DUITKU_INTEGRATION.md` - Duitku payment gateway setup
- `SUMMARY.md` - Complete feature checklist & specifications

---

## 📝 Common Modifications

### Adding a New Feature
1. Create migration: `php artisan make:migration`
2. Create model: `php artisan make:model ModelName -m`
3. Add controller & routes
4. Create tests in `tests/Feature/`
5. Update documentation

### Adding a New Report
1. Extend `DailyReport` model dengan new fields
2. Create migration untuk new columns
3. Update report calculation logic
4. Create controller endpoint
5. Create Blade template / Vue component

### Integrating New Payment Method
1. Add new payment provider class
2. Implement payment processing logic
3. Create webhook handler untuk payment confirmation
4. Add tests for payment flow
5. Update subscription approval logic

---

## ⚙️ Environment Setup

### Requirements
- PHP 8.2+
- Composer 2.x
- Node.js 18+
- MySQL 8.0+ or PostgreSQL 14+
- Docker & Docker Compose (optional, for Sail)

### Quick Start
```bash
git clone <repo-url>
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

### Running Tests
```bash
php artisan test                    # Run all tests
php artisan test --filter=Feature   # Run feature tests only
php artisan test --filter=Unit      # Run unit tests only
```

---

## 🎓 Learning Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Pest PHP Documentation](https://pestphp.com)
- [Duitku API Documentation](https://docs.duitku.com)
- [Multi-Tenancy in Laravel](https://laravel.io/articles/multi-tenancy-laravel)

---

**Last Updated**: December 27, 2025
**Maintained By**: Development Team
