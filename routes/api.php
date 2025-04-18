<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::prefix('cart')->group(function() {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::post('/remove', [CartController::class, 'remove']);
    Route::post('/clear', [CartController::class, 'clear']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [OrderController::class, 'checkout']);
});

