<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\DashboardController;
use App\Http\Controllers\Superadmin\OrganizationController;
use App\Http\Controllers\Superadmin\SubscriptionController;
use App\Http\Controllers\Superadmin\SystemSettingController;
use App\Http\Controllers\Superadmin\RevenueController;
use App\Http\Controllers\Superadmin\UserController;

Route::middleware(['auth', 'superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Organizations Management
    Route::resource('organizations', OrganizationController::class);

    // Users Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Subscriptions Management
    Route::resource('subscriptions', SubscriptionController::class);
    Route::post('subscriptions/{subscription}/approve', [SubscriptionController::class, 'approve'])->name('subscriptions.approve');
    Route::post('subscriptions/{subscription}/reject', [SubscriptionController::class, 'reject'])->name('subscriptions.reject');

    // Revenue Reports
    Route::get('/revenue', [RevenueController::class, 'index'])->name('revenue.index');

    // System Settings
    Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SystemSettingController::class, 'update'])->name('settings.update');
});
