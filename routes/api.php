<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\Order\CheckoutOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Order\HistoryOrderController;
use App\Http\Controllers\Order\StatusOrderController;

Route::prefix('cart')->group(function() {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::post('/remove', [CartController::class, 'remove']);
    Route::post('/clear', [CartController::class, 'clear']);
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('order')->group(function() {
        Route::post('/checkout', [CheckoutOrderController::class, 'checkout']);
        Route::get('/history', [HistoryOrderController::class, 'index']);
        Route::patch('/status', StatusOrderController::class);
    });
});

