<?php

namespace App\Console;

use App\Models\Logs\AppErrorLog;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Eski xatolar jurnalini (IP, user agent) o'chirish -
        // Maxfiylik siyosati 5.3-band. Scheduler konteyneri: docker-compose.prod.yml.
        $schedule->command('model:prune', ['--model' => [AppErrorLog::class]])
            ->dailyAt('03:30')
            ->onOneServer()
            ->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
