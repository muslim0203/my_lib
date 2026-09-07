<?php

use App\Http\Controllers\Web\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::post('login', [AuthController::class, 'login'])
        ->name('auth.login');

    Route::get('login', [AuthController::class, 'view'])
        ->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])
        ->name('auth.logout');
});
