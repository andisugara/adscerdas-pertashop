<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\TankAdditionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\DepositController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Settings - Owner only
    Route::resource('settings', SettingController::class)->middleware('role:owner');

    // Shifts - Owner only
    Route::resource('shifts', ShiftController::class)->middleware('role:owner');

    // Daily Reports
    Route::resource('daily-reports', DailyReportController::class);

    // Tank Additions
    Route::resource('tank-additions', TankAdditionController::class);

    // Expenses
    Route::resource('expenses', ExpenseController::class);

    // Deposits
    Route::resource('deposits', DepositController::class);
});

require __DIR__ . '/auth.php';
