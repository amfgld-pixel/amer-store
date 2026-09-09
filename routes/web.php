<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ContentPageController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/shop', [StorefrontController::class, 'shop'])->name('shop');
Route::get('/collections/{category:slug}', [StorefrontController::class, 'shop'])->name('collection.show');
Route::get('/products/{product:slug}', [StorefrontController::class, 'product'])->name('product.show');
Route::get('/pages/{page:slug}', [ContentPageController::class, 'show'])->name('pages.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{variant}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{variant}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{variant}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/{product}', [CompareController::class, 'toggle'])->name('compare.toggle');
Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order-confirmed/{uuid}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/pay/{uuid}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/pay/{uuid}', [PaymentController::class, 'initiate'])->middleware('throttle:10,1')->name('payment.initiate');
Route::get('/payment-complete/{uuid}', [PaymentController::class, 'complete'])->name('payment.complete');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/account', [CustomerAccountController::class, 'index'])->name('customer.account');
    Route::get('/account/orders/{order}', [CustomerAccountController::class, 'show'])->name('customer.orders.show');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', fn () => redirect()->route(auth()->user()?->hasRole('super-admin') ? 'admin.dashboard' : 'customer.account'))->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/admin.php';
require __DIR__.'/catalog.php';
require __DIR__.'/inventory.php';
require __DIR__.'/pos.php';
require __DIR__.'/pos-sync.php';
require __DIR__.'/order-care.php';
require __DIR__.'/marketing.php';
require __DIR__.'/operations.php';
require __DIR__.'/auth.php';
require __DIR__.'/pack-016-commercial.php';
