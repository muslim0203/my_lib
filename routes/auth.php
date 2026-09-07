<?php

use App\Http\Controllers\Web\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    /*
     * S2b (4): admin login uchun rate limit.
     *
     * Ilgari `POST /login` hech qanday cheklovsiz edi, ya'ni parolni
     * cheksiz marta taxmin qilish mumkin edi (`api` guruhidagi
     * `throttle:api` bu marshrutga tegishli emas).
     *
     * Bu yerda nomlangan limiter emas, inline limiter ishlatilgan, chunki
     * nomlangan limiter App\Providers\RouteServiceProvider da ro'yxatdan
     * o'tkazilishi kerak (u boshqa egaga tegishli fayl).
     *
     * `throttle:5,1` = bir IP + marshrut uchun daqiqasiga 5 urinish.
     */
    Route::post('login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('auth.login');

    Route::get('login', [AuthController::class, 'view'])
        ->name('login');
});

Route::middleware('auth')->group(function () {
    /*
     * S2b (2): logout holatni o'zgartiruvchi amal, shuning uchun
     * `Route::get` emas, `Route::post`. GET bo'lganida istalgan sahifadagi
     * `<img src="/logout">` foydalanuvchini tizimdan chiqarib yuborardi
     * (CSRF: Laravel GET so'rovlarni umuman tekshirmaydi).
     *
     * Chaqiruvchi joy yangilandi:
     * resources/views/components/account/profile.blade.php - endi u
     * @csrf tokenli forma yuboradi.
     */
    Route::post('logout', [AuthController::class, 'logout'])
        ->name('auth.logout');
});
