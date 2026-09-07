<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Providers\Auth\AdminProvider;
use App\Providers\Auth\EmailUserProvider;
use App\Providers\Auth\GoogleUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
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
    }
}
