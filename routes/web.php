<?php

declare(strict_types=1);

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', static fn() => view('welcome'));
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
