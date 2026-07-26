<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\CustomerController;

Route::middleware('auth')->group(function () {
    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index')->middleware('permission:customers.view');
    Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show')->middleware('permission:customers.view');
    Route::post('customers/{customer}/assign', [CustomerController::class, 'assign'])->name('customers.assign')->middleware('permission:customers.assign');
    Route::post('customers/{customer}/unassign', [CustomerController::class, 'unassign'])->name('customers.unassign')->middleware('permission:customers.assign');
    Route::post('customers/{customer}/reengage', [CustomerController::class, 'reengage'])->name('customers.reengage')->middleware('permission:customers.reengage');
});
