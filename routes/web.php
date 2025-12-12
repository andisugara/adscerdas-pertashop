<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\TankAdditionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\OrganizationSelectorController;
use App\Http\Controllers\Auth\OwnerRegistrationController;
use App\Http\Controllers\DuitkuController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ReceiptController;

// Owner Registration
Route::get('/register/owner', [OwnerRegistrationController::class, 'showRegistrationForm'])->name('owner.register.form');
Route::post('/register/owner', [OwnerRegistrationController::class, 'register'])->name('owner.register');

Route::get('/', function () {
    // Jika user sudah login, redirect sesuai role
    if (Auth::check()) {
        if (Auth::user()->isSuperadmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        return redirect()->route('dashboard');
    }
    // Jika belum login, tampilkan landing page
    return view('welcome');
});

// Organization selector (auth but no subscription check)
Route::middleware(['auth'])->group(function () {
    Route::get('/organizations/select', [OrganizationSelectorController::class, 'select'])->name('organizations.select');
    Route::post('/organizations/switch', [OrganizationSelectorController::class, 'switch'])->name('organizations.switch');
    Route::get('/subscription/expired', [OrganizationSelectorController::class, 'expired'])->name('subscription.expired');

    // Subscription (available even when expired)
    Route::get('/subscription/plans', [SubscriptionController::class, 'plans'])->name('subscription.plans');
    Route::post('/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
    Route::get('/subscription/{subscription}/manual-payment', [SubscriptionController::class, 'manualPayment'])->name('subscription.manual.payment');
    Route::post('/subscription/{subscription}/upload-proof', [SubscriptionController::class, 'uploadProof'])->name('subscription.manual.upload-proof');
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index')->middleware('role:owner');

    // Duitku Payment
    Route::get('/subscription/{subscription}/duitku-payment', [DuitkuController::class, 'createPayment'])->name('subscription.duitku.payment');
    Route::get('/duitku/return', [DuitkuController::class, 'return'])->name('duitku.return');
});

// Duitku Callback (no auth required)
Route::post('/duitku/callback', [DuitkuController::class, 'callback'])->name('duitku.callback');

Route::middleware(['auth', 'subscription'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Organizations (Pertashop) - Owner only
    Route::resource('organizations', OrganizationController::class)->except(['show', 'destroy'])->middleware('role:owner');
    Route::post('/organizations/{organization}/switch', [OrganizationController::class, 'switchOrganization'])->name('organizations.switch');

    // Operators - Owner only
    Route::resource('operators', OperatorController::class)->except(['show'])->middleware('role:owner');
    Route::post('/operators/{operator}/toggle-status', [OperatorController::class, 'toggleStatus'])->name('operators.toggle-status')->middleware('role:owner');

    // Receipts - Owner and Operator
    Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::post('/receipts/calculate', [ReceiptController::class, 'calculate'])->name('receipts.calculate');
    Route::post('/receipts/print', [ReceiptController::class, 'print'])->name('receipts.print');

    // Reports (Laporan) - Owner only
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:owner');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily')->middleware('role:owner');

    // Settings - Owner only (deprecated, will use organizations settings)
    Route::resource('settings', SettingController::class)->middleware('role:owner');

    // Shifts - Owner only
    Route::resource('shifts', ShiftController::class)->middleware('role:owner');

    // Daily Reports - Accessible by both owner and operator
    Route::resource('daily-reports', DailyReportController::class);

    // Tank Additions - Accessible by both owner and operator
    Route::resource('tank-additions', TankAdditionController::class);

    // Expenses - Owner only
    Route::resource('expenses', ExpenseController::class)->middleware('role:owner');

    // Deposits - Owner only
    Route::resource('deposits', DepositController::class)->middleware('role:owner');

    // Salaries - Owner only
    Route::resource('salaries', SalaryController::class)->middleware('role:owner');
});

require __DIR__ . '/auth.php';
