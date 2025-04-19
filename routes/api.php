<?php

declare(strict_types=1);

use App\Http\Controllers\CartController;
use App\Http\Controllers\Order\CheckoutOrderController;
use App\Http\Controllers\Order\HistoryOrderController;
use App\Http\Controllers\Order\StatusOrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart')->group(static function (): void {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::post('/remove', [CartController::class, 'remove']);
    Route::post('/clear', [CartController::class, 'clear']);
});
Route::middleware('auth:sanctum')->group(static function (): void {
    Route::prefix('order')->group(static function (): void {
        Route::post('/checkout', [CheckoutOrderController::class, 'checkout']);
        Route::get('/history', [HistoryOrderController::class, 'index']);
        Route::patch('/status', StatusOrderController::class);
    });
});
