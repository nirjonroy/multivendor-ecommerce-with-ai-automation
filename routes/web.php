<?php

use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Backend\Auth\RegisteredAdminController;
use App\Http\Controllers\Frontend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('frontend.home');
})->name('home');

Route::middleware('guest:web,vendor')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth:web')->group(function () {
    Route::get('dashboard', function () {
        return view('frontend.dashboard.user');
    })->name('dashboard');
});

Route::middleware('auth:vendor')->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('dashboard', function () {
        return view('frontend.dashboard.vendor');
    })->name('dashboard');
});

Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth:web,vendor')
    ->name('logout');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('login', [AdminAuthenticatedSessionController::class, 'store']);
        Route::get('register', [RegisteredAdminController::class, 'create'])->name('register');
        Route::post('register', [RegisteredAdminController::class, 'store']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', function () {
            return view('backend.dashboard.index');
        })->name('dashboard');

        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
