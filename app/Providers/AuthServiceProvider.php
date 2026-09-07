<?php

namespace App\Providers;

use App\Providers\Auth\AdminProvider;
use App\Providers\Auth\EmailUserProvider;
use App\Providers\Auth\GoogleUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Panelda hamma narsaga ruxsat beruvchi rol nomi (`web` guard).
     */
    public const SUPER_ROLE = 'admin';

    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::provider(config('auth.driver_mail'), function () {
            return new EmailUserProvider();
        });

        Auth::provider(config('auth.driver_google'), function () {
            return new GoogleUserProvider();
        });

        Auth::provider(config('auth.driver_admin'), function () {
            return new AdminProvider();
        });

        $this->registerAdminSuperRole();
    }

    /**
     * `admin` roliga ega foydalanuvchi barcha huquqlarga ega bo'ladi.
     *
     * DIQQAT: bu yerda ATAYLAB `employee_id` tekshirilmaydi. Aynan
     * "employee_id bor -> hamma narsa mumkin" qoidasi dastlabki xatolik
     * edi; uni takrorlamaslik uchun yagona shart - `admin` rolining
     * mavjudligi. Mavjud bazadagi adminlar bloklanib qolmasligi uchun
     * bir marta quyidagi buyruq bajariladi:
     *
     *   php artisan db:seed --class=Database\\Seeders\\RoleSeeder
     *
     * Gate::before `null` qaytarsa, tekshiruv odatdagidek davom etadi
     * (spatie huquqlari, policy'lar va h.k.).
     */
    private function registerAdminSuperRole(): void
    {
        Gate::before(function ($user, string $ability) {
            if (!is_object($user) || !method_exists($user, 'hasRole')) {
                return null;
            }

            try {
                return $user->hasRole(self::SUPER_ROLE) ? true : null;
            } catch (\Throwable) {
                // Rollar jadvali hali migratsiya qilinmagan bo'lishi mumkin:
                // bunday holatda oddiy tekshiruvga o'tamiz (fail closed).
                return null;
            }
        });
    }
}
