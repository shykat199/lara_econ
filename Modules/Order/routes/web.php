<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::middleware('auth')->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index')->middleware('permission:orders.view');
    Route::get('orders/create', [OrderController::class, 'create'])->name('orders.create')->middleware('permission:orders.create');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store')->middleware('permission:orders.create');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('permission:orders.view');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy')->middleware('permission:orders.delete');
});
