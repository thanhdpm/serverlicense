<?php

use App\Http\Controllers\Api\LicenseController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('license', LicenseController::class)->name('api.license');
Route::get('product', ProductController::class)->name('api.product');
