<?php

use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController as AdminAuthenticatedSessionController;
use App\Http\Controllers\Backend\Auth\RegisteredAdminController;
use App\Http\Controllers\Backend\CatalogTaxonomyController;
use App\Http\Controllers\Backend\HomeSectionController;
use App\Http\Controllers\Backend\ProductController as AdminProductController;
use App\Http\Controllers\Backend\ProductOptionController;
use App\Http\Controllers\Backend\SiteInfoController;
use App\Http\Controllers\Backend\VendorController as AdminVendorController;
use App\Http\Controllers\Frontend\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Frontend\Auth\RegisteredUserController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\ShopSettingsController;
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

Route::get('products/{product:slug}', [FrontendProductController::class, 'show'])->name('products.show');
Route::get('cart', [CartController::class, 'index'])->name('cart.index');
Route::post('cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::post('buy-now/{product}', [CartController::class, 'buyNow'])->name('cart.buy-now');
Route::get('checkout', [CartController::class, 'checkout'])->name('checkout.index');

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
    Route::get('dashboard', VendorDashboardController::class)->name('dashboard');
    Route::get('shop-settings', [ShopSettingsController::class, 'edit'])->name('shop-settings.edit');
    Route::put('shop-settings', [ShopSettingsController::class, 'update'])->name('shop-settings.update');
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

        Route::get('site-info', [SiteInfoController::class, 'edit'])->name('site-info.edit');
        Route::put('site-info', [SiteInfoController::class, 'update'])->name('site-info.update');
        Route::get('home-section', [HomeSectionController::class, 'edit'])->name('home-section.edit');
        Route::put('home-section', [HomeSectionController::class, 'update'])->name('home-section.update');
        Route::get('catalog/{resource}', [CatalogTaxonomyController::class, 'index'])->name('catalog.index');
        Route::get('catalog/{resource}/create', [CatalogTaxonomyController::class, 'create'])->name('catalog.create');
        Route::post('catalog/{resource}', [CatalogTaxonomyController::class, 'store'])->name('catalog.store');
        Route::get('catalog/{resource}/{id}/edit', [CatalogTaxonomyController::class, 'edit'])->name('catalog.edit');
        Route::put('catalog/{resource}/{id}', [CatalogTaxonomyController::class, 'update'])->name('catalog.update');
        Route::delete('catalog/{resource}/{id}', [CatalogTaxonomyController::class, 'destroy'])->name('catalog.destroy');
        Route::resource('products', AdminProductController::class)->except(['show']);
        Route::get('vendors', [AdminVendorController::class, 'index'])->name('vendors.index');
        Route::get('vendors/{vendor}', [AdminVendorController::class, 'show'])->name('vendors.show');
        Route::patch('vendors/{vendor}/approve', [AdminVendorController::class, 'approve'])->name('vendors.approve');
        Route::patch('vendors/{vendor}/reject', [AdminVendorController::class, 'reject'])->name('vendors.reject');
        Route::get('product-options/{resource}', [ProductOptionController::class, 'index'])->name('product-options.index');
        Route::get('product-options/{resource}/create', [ProductOptionController::class, 'create'])->name('product-options.create');
        Route::post('product-options/{resource}', [ProductOptionController::class, 'store'])->name('product-options.store');
        Route::get('product-options/{resource}/{id}/edit', [ProductOptionController::class, 'edit'])->name('product-options.edit');
        Route::put('product-options/{resource}/{id}', [ProductOptionController::class, 'update'])->name('product-options.update');
        Route::delete('product-options/{resource}/{id}', [ProductOptionController::class, 'destroy'])->name('product-options.destroy');

        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
    });
});
