<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('main.index');
    Route::get('/news', [MainController::class, 'getNews'])->name('main.news');
});

require __DIR__ . '/auth.php';
