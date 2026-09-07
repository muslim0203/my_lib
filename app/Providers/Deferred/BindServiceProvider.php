<?php

namespace App\Providers\Deferred;

use App\Core\Repository\FileManager\FileManagerRepository;
use App\Core\Services\Auth\Interfaces\RegisterInterface;
use App\Core\Services\Auth\RegisterService;
use App\Core\Services\Author\AuthorService;
use App\Core\Services\Author\Interfaces\AuthorInterface;
use App\Core\Services\FileManager\FileManagerService;
use App\Core\Services\FileManager\Interface\FileManagerInterface;
use App\Core\Services\Mail\interfaces\MailInterface;
use App\Core\Services\Mail\MailService;
use App\Core\Services\Pay\ClickService;
use App\Core\Services\Pay\Contracts\ClickContract;
use App\Core\Services\Pay\Contracts\PaymentInterface;
use App\Core\Services\Pay\PaymeService;
use App\Core\Services\Reports\Contracts\ReportInterface;
use App\Core\Services\Reports\ReportService;
use App\Core\Services\User\Interfaces\UserInterface;
use App\Core\Services\User\UserService;
use App\Http\Controllers\Api\Pay\ClickController;
use App\Http\Controllers\Api\Pay\PaymeController;
use App\Mail\VerifyMail;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class BindServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(MailInterface::class, function () {
            // Kriptografik jihatdan xavfsiz olti xonali kod. rand() taxmin
            // qilinadigan ketma-ketlik bergani uchun ishlatilmaydi.
            $token = random_int(100000, 999999);

            return new MailService($token, new VerifyMail($token));
        });

        $this->app->bind(RegisterInterface::class, RegisterService::class);

        $this->app->bind(UserInterface::class, UserService::class);

        $this->app->bind(FileManagerInterface::class, function () {
            return new FileManagerService(
                fileManagerRepository: new FileManagerRepository(),
                path: config('filesystems.upload_path'),
                hash: config('filesystems.hash')
            );
        });

        $this->app->bind(AuthorInterface::class, AuthorService::class);

        $this->app
            ->when(PaymeService::class)
            ->needs('$merchantId')
            ->give(fn () => config('payme.merchant_id'));

        $this->app
            ->when(PaymeService::class)
            ->needs('$login')
            ->give(fn () => config('payme.login'));

        $this->app
            ->when(PaymeService::class)
            ->needs('$key')
            ->give(fn () => config('payme.key'));

        $this->app
            ->when(PaymeService::class)
            ->needs('$url')
            ->give(fn () => config('payme.url'));

        $this->app
            ->when(PaymeController::class)
            ->needs(PaymentInterface::class)
            ->give(PaymeService::class);

        $this->app
            ->when(ClickController::class)
            ->needs(ClickContract::class)
            ->give(ClickService::class);

        $this->app->bind(ReportInterface::class, ReportService::class);
    }

    public function provides(): array
    {
        return [
            MailInterface::class,
            RegisterInterface::class,
            UserInterface::class,
            FileManagerInterface::class,
            AuthorInterface::class,
            PaymentInterface::class,
            ClickContract::class,
            ReportInterface::class,
        ];
    }
}
