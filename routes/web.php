<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('main.index');
    Route::get('/news', [MainController::class, 'getNews'])->name('main.news');

    Route::middleware('can:admin')->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/codes', [AdminController::class, 'codes'])->name('admin.codes');
        Route::get('/news', [AdminController::class, 'news'])->name('admin.news');
    });
});

require __DIR__ . '/auth.php';
