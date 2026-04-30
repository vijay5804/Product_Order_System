<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return redirect('/products');
});

Route::resource('products', ProductController::class);
Route::resource('users', UserController::class);

Route::get('orders', [OrderController::class, 'index']);
Route::get('orders/create', [OrderController::class, 'create']);
Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{id}', [OrderController::class, 'show']);

Route::delete('orders/{id}', [OrderController::class, 'destroy']); 
Route::put('orders/{id}/status', [OrderController::class, 'updateStatus']);

Route::get('/search-products', [ProductController::class, 'search']);

Route::get('/test/{id}',[ProductController::class,'updatenew']);