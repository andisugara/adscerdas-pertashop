# Pertashop SaaS Platform - Deployment Guide

## 🎯 Overview

Pertashop telah berhasil di-upgrade menjadi platform SaaS multi-tenant dengan fitur:

-   Multi-organization support (1 owner bisa kelola banyak pertashop)
-   Subscription management (Trial, Monthly, Yearly)
-   Dual payment system (Manual approval & Duitku)
-   Superadmin panel untuk management
-   Organization switcher di navbar

## 📋 Requirements

-   PHP 8.2+
-   MySQL 8.0+
-   Composer
-   Node.js & NPM

## 🚀 Installation

### 1. Clone & Install Dependencies

```bash
git clone <repository-url>
cd adscerdas-pertashop
composer install
npm install
npm run build
```

### 2. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Configure `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pertashop_saas
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

### 3. Database Migration & Seeding

```bash
php artisan migrate:fresh --seed
```

This will create:

-   **Superadmin Account**: superadmin@pertashop.com / password
-   **Demo Owner**: owner@pertashop.com / password
-   **Demo Operator**: operator@pertashop.com / password
-   **Demo Organization**: Pertashop Demo (with 14-day trial)
-   **System Settings**: Payment gateway, pricing, trial days

### 4. Storage Link

```bash
php artisan storage:link
```

### 5. Run Development Server

```bash
php artisan serve
```

## 👥 Default Accounts

### Superadmin

-   Email: `superadmin@pertashop.com`
-   Password: `password`
-   Access: `/superadmin/dashboard`

### Demo Owner

-   Email: `owner@pertashop.com`
-   Password: `password`
-   Organization: Pertashop Demo

### Demo Operator

-   Email: `operator@pertashop.com`
-   Password: `password`
-   Organization: Pertashop Demo

## 🏗️ Architecture

### Multi-Tenancy Pattern

-   **Shared Database**: Semua organization dalam 1 database
-   **Organization ID**: Setiap table punya `organization_id`
-   **Global Scope**: Auto-filter query berdasarkan active organization
-   **Trait**: `BelongsToOrganization` untuk auto-assign organization_id

### User Roles

1. **Superadmin**: Full access ke semua organization
2. **Owner**: Kelola organization sendiri, bisa multiple
3. **Operator**: Operasional harian per organization

### Subscription Flow

```
Trial (14 days, auto-approved)
    ↓
Choose Plan (Monthly/Yearly)
    ↓
Payment Method
    ├─ Manual Transfer → Upload Proof → Waiting Approval
    └─ Duitku → Auto Payment → Auto Approved
```

## 🔧 Configuration

### System Settings (via Superadmin)

Path: `/superadmin/settings`

-   **Payment Gateway**: manual / duitku
-   **Trial Days**: Default 14 days
-   **Monthly Price**: Rp 100,000
-   **Yearly Price**: Rp 1,000,000
-   **Duitku Credentials**: Merchant Code, API Key, Callback URL

### Duitku Integration (Optional)

1. Login ke `/superadmin/settings`
2. Set Payment Gateway = "duitku"
3. Input Duitku Merchant Code
4. Input Duitku API Key
5. Set Callback URL: `https://your-domain.com/duitku/callback`

## 📱 Features

### For Owner

✅ Register with organization info
✅ 14-day free trial
✅ Switch between multiple organizations
✅ Subscribe (Monthly/Yearly)
✅ Upload payment proof (Manual)
✅ Renew subscription when expired

### For Superadmin

✅ View all organizations
✅ View all subscriptions
✅ Approve/Reject manual payments
✅ View payment proofs
✅ Manage system settings
✅ Dashboard with stats

### For Operator

✅ Daily operations (shifts, reports, expenses)
✅ View only assigned organization data
✅ No access to subscriptions

## 🗂️ Database Schema

### Key Tables

-   `organizations` - Organization data
-   `subscriptions` - Subscription & payment records
-   `organization_user` - Pivot table (user-org-role)
-   `system_settings` - Global configuration
-   All existing tables now have `organization_id`

### Important Fields

-   `users.active_organization_id` - Current active org
-   `organizations.trial_ends_at` - Trial expiration
-   `subscriptions.payment_proof` - Upload bukti transfer
-   `subscriptions.status` - pending/active/expired/cancelled
-   `subscriptions.payment_status` - pending/paid/failed

## 🔐 Middleware

### `subscription`

-   Checks if user has active organization
-   Verifies organization is active
-   Allows trial period
-   Requires active subscription
-   Redirects to expired page if needed

### `superadmin`

-   Checks if user role is 'superadmin'
-   Aborts 403 if not

## 🛣️ Routes

### Public Routes

-   `/register/owner` - Owner registration
-   `/login` - Login page

### Auth Routes (No Subscription Check)

-   `/organizations/select` - Organization selector
-   `/organizations/switch` - Switch organization
-   `/subscription/plans` - Choose subscription plan
-   `/subscription/{id}/manual-payment` - Manual payment page
-   `/subscription/expired` - Expired notification

### Protected Routes (Requires Subscription)

-   `/dashboard` - Main dashboard
-   `/laporan/*` - Reports (Owner only)
-   `/settings/*` - Settings
-   `/shifts/*` - Shift management
-   `/daily-reports/*` - Daily reports
-   `/tank-additions/*` - Tank additions
-   `/expenses/*` - Expenses
-   `/deposits/*` - Deposits
-   `/salaries/*` - Salaries

### Superadmin Routes

-   `/superadmin/dashboard` - Stats overview
-   `/superadmin/organizations` - Manage organizations
-   `/superadmin/subscriptions` - Manage subscriptions
-   `/superadmin/subscriptions/{id}/approve` - Approve payment
-   `/superadmin/subscriptions/{id}/reject` - Reject payment
-   `/superadmin/settings` - System configuration

## 🎨 UI Components

### Organization Switcher (Navbar)

-   Shows current active organization
-   Dropdown list of all user's organizations
-   One-click switch between organizations
-   Hidden for superadmin

### Organization Selector

-   Grid of organization cards
-   Shows status badge (Trial/Active/Expired)
-   Auto-select if only 1 organization
-   Switch button for each org

### Subscription Expired Page

-   Shows when subscription ends
-   Renew button (for owners)
-   Logout option

## 📧 TODO: Email Notifications

### Planned (Not Yet Implemented)

-   [ ] Welcome email on registration
-   [ ] Trial expiration warning (3 days before)
-   [ ] Subscription expiration warning (7 days before)
-   [ ] Payment received confirmation
-   [ ] Subscription approved notification
-   [ ] Subscription rejected notification

## 🔄 Testing Workflow

### As Owner

1. Register at `/register/owner`
2. Input personal + organization data
3. Choose Trial plan (free 14 days)
4. Login → Auto-redirected to dashboard
5. Use system normally for 14 days
6. When trial expires → Redirected to `/subscription/expired`
7. Click "Renew" → Choose plan (Monthly/Yearly)
8. Choose payment method (Manual/Duitku)
9. Upload payment proof
10. Wait for superadmin approval

### As Superadmin

1. Login at `/login` (superadmin@pertashop.com)
2. Go to `/superadmin/dashboard`
3. View pending subscriptions
4. Click subscription → View payment proof
5. Approve or Reject
6. User gets access immediately after approval

## 🚨 Important Notes

1. **Trial Auto-Approved**: Trial subscriptions langsung active
2. **Paid Pending**: Monthly/Yearly subscriptions status=pending sampai di-approve
3. **Organization Scope**: Semua query auto-filtered by organization_id
4. **Superadmin Bypass**: Superadmin bisa lihat semua data tanpa filter
5. **Payment Proof**: Stored di `storage/app/public/payment-proofs/`

## 📂 Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   └── OwnerRegistrationController.php
│   │   ├── Superadmin/
│   │   │   ├── DashboardController.php
│   │   │   ├── OrganizationController.php
│   │   │   ├── SubscriptionController.php
│   │   │   └── SystemSettingController.php
│   │   ├── OrganizationSelectorController.php
│   │   └── SubscriptionController.php
│   └── Middleware/
│       ├── CheckSubscription.php
│       └── SuperadminMiddleware.php
├── Models/
│   ├── Organization.php
│   ├── Subscription.php
│   ├── SystemSetting.php
│   ├── Scopes/
│   │   └── OrganizationScope.php
│   └── Traits/
│       └── BelongsToOrganization.php
resources/
├── views/
│   ├── auth/
│   │   └── owner-register.blade.php
│   ├── organizations/
│   │   ├── select.blade.php
│   │   └── no-access.blade.php
│   ├── subscription/
│   │   ├── plans.blade.php
│   │   ├── manual-payment.blade.php
│   │   └── expired.blade.php
│   └── superadmin/
│       ├── layout.blade.php
│       ├── dashboard.blade.php
│       ├── organizations/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       ├── subscriptions/
│       │   ├── index.blade.php
│       │   └── show.blade.php
│       └── settings/
│           └── index.blade.php
routes/
├── web.php
└── superadmin.php
database/
├── migrations/
│   ├── 2025_12_09_111857_create_organizations_table.php
│   ├── 2025_12_09_111906_create_subscriptions_table.php
│   ├── 2025_12_09_111917_create_organization_user_table.php
│   ├── 2025_12_09_111928_add_organization_id_to_all_tables.php
│   └── 2025_12_09_112101_create_system_settings_table.php
└── seeders/
    ├── SuperadminSeeder.php
    ├── SystemSettingSeeder.php
    └── DemoOrganizationSeeder.php
```

## 🎉 Success!

Platform SaaS multi-tenant Pertashop siap digunakan!

**Next Steps:**

1. Setup production database
2. Configure email SMTP
3. Setup Duitku credentials (optional)
4. Test registration flow
5. Launch! 🚀
