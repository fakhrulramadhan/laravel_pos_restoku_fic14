<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//auth:sanctum = hanya user yang sudah login, yang bisa akses api tsb, routenya 127.0.0.1:8000/api

//login api
Route::post('/login', [AuthController::class, 'login']);

// logout api
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ==== PART DUA ==========

// products api
Route::get('/products', [ProductController::class, 'index'])->middleware('auth:sanctum');
Route::post('/products', [ProductController::class, 'store'])->middleware('auth:sanctum');
Route::post('/products/edit', [ProductController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->middleware('auth:sanctum');
// Route::post('/products/edit/{id}', function ($id) {
    
// });
Route::apiResource('/api-categories', CategoryController::class)->middleware('auth:sanctum');

Route::post('/save-order', [OrderController::class, 'saveOrder'])->middleware('auth:sanctum');

Route::get('/api-discounts', [DiscountController::class, 'index'])->middleware('auth:sanctum');

Route::post('/api-discounts', [DiscountController::class, 'store'])->middleware('auth:sanctum');

// api untuk report pdf, date? blm tentu tgl nya ada
Route::get('/orders/{date?}', [OrderController::class, 'index'])->middleware('auth:sanctum');
Route::get('/summary/{date?}', [OrderController::class, 'summary'])->middleware('auth:sanctum');
Route::get('/order-item/{date?}', [OrderItemController::class, 'index'])->middleware('auth:sanctum');
Route::get('/order-sales', [OrderItemController::class, 'orderSales'])->middleware('auth:sanctum');

// ========= PART SATU ==========

//categories api
// Route::apiResource('/api-categories', CategoryController::class)->middleware('auth:sanctum');

// Route::apiResource('/api-products', ProductController::class)->middleware('auth:sanctum');

// // orders api
// Route::post('/save-order', [OrderController::class, 'saveOrder'])->middleware('auth:sanctum');

// Route::get('/api-discounts', [DiscountController::class, 'index'])->middleware('auth:sanctum');

// Route::post('/api-discounts', [DiscountController::class, 'store'])->middleware('auth:sanctum');

