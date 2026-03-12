<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CheckoutController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', function () {
    return inertia('Cart');
})->name('cart');

// Printer Setup
Route::get('/printer-setup', function () {
    return inertia('PrinterSetup');
})->name('printer.setup');

// Checkout
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

// Redirect authentication routes to Filament admin
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/register', function () {
    return redirect('/admin/register');
})->name('register');

Route::post('/logout', function () {
    return redirect('/admin/logout');
})->name('logout');
