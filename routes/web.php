<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VersionController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');

    Route::view('/', 'dashboard')->name('dashboard');

    // Every non-GET request below is rejected while APP_DEMO is enabled.
    Route::middleware('demo')->group(function () {
        Route::get('change-password', [PasswordController::class, 'edit'])->name('password.edit');
        Route::put('change-password', [PasswordController::class, 'update'])->name('password.update');

        Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
        Route::resource('customers', CustomerController::class)
            ->missing(fn () => to_route('customers.index')->withErrors('Không tìm thấy hồ sơ khách hàng :('));

        Route::resource('products', ProductController::class)->except(['create', 'show']);
        Route::resource('products.versions', VersionController::class)->shallow()->except(['create', 'show']);

        Route::resource('licenses', LicenseController::class)->except('show');
    });
});
