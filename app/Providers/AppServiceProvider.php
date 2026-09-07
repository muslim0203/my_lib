<?php

namespace App\Providers;

use App\Core\Services\Auth\Interfaces\RegisterInterface;
use App\Core\Services\Auth\RegisterService;
use App\Core\Services\Mail\interfaces\MailInterface;
use App\Core\Services\Mail\MailService;
use App\Mail\VerifyMail;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        Paginator::useBootstrap();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
