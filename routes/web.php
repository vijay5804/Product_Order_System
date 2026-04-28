<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

// Home Redirect
Route::get('/', function () {
    return redirect('/products');
});

// Products & Users (Using Resources for full CRUD)
Route::resource('products', ProductController::class);
Route::resource('users', UserController::class);

// Orders - Manual Routes
Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/create', [OrderController::class, 'create']);
Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{id}', [OrderController::class, 'show']);

// 🔥 Essential for Delete & Status Update
Route::delete('orders/{id}', [OrderController::class, 'destroy']); 
Route::put('orders/{id}/status', [OrderController::class, 'updateStatus']);

// Product Live Search
Route::get('/search-products', [ProductController::class, 'search']);