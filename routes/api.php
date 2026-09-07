<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


require __DIR__.'/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    //User
    Route::get('/user',[UserController::class, 'getUser']);

    // Products 
    Route::get('/products/{categoryId}/', [ProductController::class, 'index']);
    Route::get('/products', [ProductController::class, 'getByBarcode']);
    Route::post('/products/add', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/products/{id}/barcode', [ProductController::class, 'getByBarcode']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'getCategory']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Sales / POS
    Route::get('/sales', [SaleController::class, 'index']);
    Route::post('/sales/checkout', [SaleController::class, 'checkout']); // Checkout
    Route::get('/sales/{id}', [SaleController::class, 'show']);
    Route::put('/sales/{id}/void', [SaleController::class, 'void']);

    // Stock Management
    Route::get('/stock', [StockController::class, 'getProductLog']);
    Route::post('/stock/in', [StockController::class, 'stockIn']);
    Route::put('/stock/adjust/{id}', [StockController::class, 'adjust']);
    Route::get('/stock/movements', [StockController::class, 'movements']);
    Route::get('/stock/low', [StockController::class, 'lowStock']);

    // Dashboard & Analytics
    Route::get('/dashboard', [DashboardController::class, 'getDashboard']);
    Route::get('/dashboard/monthly', [DashboardController::class, 'monthly']);
    Route::get('/dashboard/week', [DashboardController::class, 'week']);


});