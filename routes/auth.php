<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('auth/register', [AuthController::class, 'registerPost']);

    Route::get('login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('auth/login', [AuthController::class, 'loginPost']);

    Route::post('auth/verify', [AuthController::class, 'verify']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('logout');
});
