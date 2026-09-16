<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\SetupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/save-setup', [SetupController::class, 'saveSetup'])->name('save_setup');

Route::middleware(['app'])->group(function () {
    Route::middleware(['guest'])->group(function () {
        Route::get('/login', [AuthController::class, 'viewLogin'])->name('auth.login');
        Route::post('/login', [AuthController::class, 'doLogin'])->name('auth.doLogin');
    });

    Route::middleware(['auth'])->group(function () {

        Route::group([
            'as' => 'auth.',
            'controller' => AuthController::class,
        ], function () {
            Route::get('/logout', 'doLogout')->name('logout');
            Route::get('/change-password', 'viewChangePwd')->name('change_pwd');
            Route::post('/change-password', 'doChangePwd')
                ->middleware('protect')
                ->name('do_change_pwd');
        });

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::group([
            'as' => 'customer.',
            'controller' => CustomerController::class
        ], function () {

            Route::group(['prefix' => 'customers',], function () {
                Route::get('/', 'index')->name('index');
                Route::get('add', 'viewAdd')->name('add');
                Route::post('add', 'doAdd')
                    ->middleware('protect')
                    ->name('do_add');
            });

            Route::group(['prefix' => 'customer'], function () {
                Route::get('{id}', 'customerProfile')->name('profile');
                Route::get('{id}/delete', 'delete')
                    ->middleware('protect')
                    ->name('delete');
                Route::get('{customer}/edit', 'edit')->name('edit');
                Route::post('{customer}/save', 'save')
                    ->middleware('protect')
                    ->name('save');
            });

        });

        Route::group([
            'as' => 'product.',
            'controller' => ProductController::class
        ], function () {

            Route::group(['prefix' => 'products'], function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'addProduct')
                    ->middleware('protect')
                    ->name('add');
            });

            Route::group([
                'prefix' => 'product'
            ], function () {
                Route::get('{product}', 'edit')->name('edit');
                Route::get('{product}/delete', 'delete')
                    ->middleware('protect')
                    ->name('delete');
                Route::post('{product}/save', 'save')
                    ->middleware('protect')
                    ->name('save');
                Route::get('version-log/{id}', 'version_log')->name('version_log');
                Route::post('version-log/{id}', 'addVersion')
                    ->middleware('protect')
                    ->name('version.add');
                Route::get('version-log/{id}/edit', 'editVersion')->name('version.edit');
                Route::get('version-log/{id}/delete', 'deleteVersion')
                    ->middleware('protect')
                    ->name('version.delete');
                Route::get('version-log/{id}/save', 'saveVersion')
                    ->middleware('protect')
                    ->name('version.save');
            });
        });

        Route::group([
            'prefix' => 'licenses',
            'as' => 'license.',
            'controller' => LicenseController::class
        ], function () {

            Route::get('/', 'index')->name('index');
            Route::get('add', 'viewAdd')->name('add');
            Route::post('add', 'doAdd')->name('do_add');
            Route::get('{id}/edit', 'edit')->name('edit');
            Route::post('{id}/save', 'save')->name('save');
            Route::get('{id}/delete', 'delete')->name('delete');

        });

    });
});
