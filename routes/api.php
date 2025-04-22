<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\StatusOrderController;
use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Order\CheckoutOrderController;
use App\Http\Controllers\Order\UserHistoryOrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminHistoryOrdersController;
use App\Http\Controllers\Admin\AdminProductController;

Route::prefix('cart')->group(static function (): void {
    Route::get('/', [CartController::class, 'index']);
    Route::post('/add', [CartController::class, 'add']);
    Route::post('/remove', [CartController::class, 'remove']);
    Route::post('/clear', [CartController::class, 'clear']);
});
Route::middleware('auth:sanctum')->group(static function (): void {
    Route::prefix('order')->group(static function (): void {
        Route::post('/checkout', [CheckoutOrderController::class, 'checkout']);
        Route::get('/history', UserHistoryOrderController::class);
    });
});
Route::prefix('admin')->group(static function (): void {
    Route::prefix('orders')->group(static function (): void {
        Route::get('/', AdminHistoryOrdersController::class);
        Route::patch('{id}/status', StatusOrderController::class);
    });
    Route::prefix('products')->group(static function (): void {
        Route::post('/add', [AdminProductController::class, 'store']);
        Route::delete('/{id}/delete', [AdminProductController::class, 'destroy']);
        Route::patch('{id}/update', [AdminProductController::class, 'update']);
    });
});
