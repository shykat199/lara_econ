<?php

use Illuminate\Support\Facades\Route;
use Modules\Acl\Http\Controllers\RoleController;

Route::middleware(['auth', 'permission:roles.manage'])->group(function () {
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
});
