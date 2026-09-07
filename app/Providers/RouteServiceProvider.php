<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // OTP chiqarish: bir xil qabul qiluvchi ham, bir xil IP ham
        // cheklanadi. Ikkala o'lchov ham kerak - faqat IP bo'yicha
        // cheklov bitta pochtaga ko'p yuborishni to'xtatmaydi, faqat
        // pochta bo'yicha cheklov esa ommaviy enumeratsiyani to'xtatmaydi.
        RateLimiter::for('otp-send', function (Request $request) {
            $email = (string)$request->input('email');

            return [
                Limit::perMinute(5)->by('otp-send:ip:' . $request->ip()),
                Limit::perMinute(2)->by('otp-send:mail:' . mb_strtolower($email)),
                Limit::perDay(20)->by('otp-send:mail-day:' . mb_strtolower($email)),
            ];
        });

        // OTP tekshirish: kodni taxminlashga qarshi.
        RateLimiter::for('otp-verify', function (Request $request) {
            $email = (string)$request->input('email');

            return [
                Limit::perMinute(10)->by('otp-verify:ip:' . $request->ip()),
                Limit::perMinute(5)->by('otp-verify:mail:' . mb_strtolower($email)),
            ];
        });

        // Admin paneli logini. Faqat IP bo'yicha cheklov bitta akkauntga
        // qaratilgan hujumni to'xtatmaydi, faqat login bo'yicha cheklov
        // esa ommaviy urinishni to'xtatmaydi - shuning uchun ikkalasi ham.
        RateLimiter::for('admin-login', function (Request $request) {
            $username = mb_strtolower((string)$request->input('username'));

            return [
                Limit::perMinute(5)->by('admin-login:ip:' . $request->ip()),
                Limit::perMinute(10)->by('admin-login:user:' . $username . '|' . $request->ip()),
            ];
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
