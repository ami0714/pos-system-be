<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


require __DIR__.'/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    //User
    Route::get('/user',[UserController::class, 'getUser']);

    // Products 
    Route::get('/products/{categoryId}/', [ProductController::class, 'index']);
    Route::get('/products', [ProductController::class, 'getByBarcode']);
    Route::post('/products/add', [ProductController::class, 'addProduct']);
    Route::put('/products/edit/{productId}', [ProductController::class, 'editProduct']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    Route::get('/products/{id}/barcode', [ProductController::class, 'getByBarcode']);

    //unit
    Route::get('/units',[UnitController::class, 'getUnits']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'getCategory']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // Sales / POS
    Route::get('/sales', [SaleController::class, 'index']);
    Route::post('/sales/checkout', [SaleController::class, 'checkout']); // Checkout
    Route::get('/sales/history', [SaleController::class, 'getSaleHistory']);
    Route::get('/sales/receipt/{id}', [SaleController::class, 'getReceipt']);
    Route::put('/sales/{id}/void', [SaleController::class, 'void']);

    // Stock Management
    Route::get('/stock', [StockController::class, 'getProductLog']);
    Route::put('/stock/in/{productId}', [StockController::class, 'stockIn']);
    Route::put('/stock/adjust/{productId}', [StockController::class, 'adjust']);
    Route::get('/stock/movements', [StockController::class, 'movements']);
    Route::get('/stock/low', [StockController::class, 'lowStock']);

    // Dashboard & Analytics
    Route::get('/dashboard', [DashboardController::class, 'getDashboard']);
    Route::get('/dashboard/monthly', [DashboardController::class, 'monthly']);
    Route::get('/dashboard/week', [DashboardController::class, 'week']);


});