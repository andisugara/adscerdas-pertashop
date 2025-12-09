<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\TankAdditionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\SalaryController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reports (Laporan) - Owner only
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index')->middleware('role:owner');
    Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily')->middleware('role:owner');

    // Settings - Owner only
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
