<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/test', function () {
    return[
        'data'=>'hello world'
    ];
});
Route::post('/products', [ProductController::class, 'create']);

