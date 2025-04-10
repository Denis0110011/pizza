<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', static fn() => view('welcome'));

Route::prefix('admin')->group(function() {
    Route::get('users', function () {
        return 'all';
    });
    Route::get('users/{id}', function ($id){
        return $id;
    })->whereNumber('id');
    Route::get('/users/profile', function () {
        return 'profile';
    })->name('profile');
});
Route::get('/user', [UserController::class, 'show']);
