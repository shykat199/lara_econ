<?php

use Illuminate\Support\Facades\Route;
use Modules\Api\Http\Controllers\AuthController;
use Modules\Api\Http\Controllers\OrderController;
use Modules\Api\Http\Controllers\ProductController;

Route::prefix('v1')->group(function () {
    Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');

    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/{slug}', [ProductController::class, 'show'])->name('products.show');

    // Order placement accepts both an authenticated customer (Bearer token)
    // and a guest checkout (name + email in the payload) — see OrderController.
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    });
});
