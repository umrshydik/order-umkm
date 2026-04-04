<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

// Mobile User Routes (Customer Ordering)
Route::get('/', [OrderController::class, 'index'])->name('home');
Route::get('/cart', [OrderController::class, 'cart'])->name('cart');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes
    Route::get('/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
        
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders.index');
        Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.update-status');

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
        
        Route::get('incomes/export', [\App\Http\Controllers\Admin\IncomeController::class, 'export'])->name('incomes.export');
        Route::get('incomes', [\App\Http\Controllers\Admin\IncomeController::class, 'index'])->name('incomes.index');
    });
});
