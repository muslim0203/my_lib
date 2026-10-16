<?php

namespace Tests\Feature;

use App\Models\Logs\AppErrorLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Maxfiylik siyosati 5.3-band: xatolar jurnalidagi IP va user agent
 * 12 oydan ortiq saqlanmaydi.
 */
class AppErrorLogPruneTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('app_error_logs', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function test_logs_older_than_retention_are_pruned(): void
    {
        $old = now()->subMonths(AppErrorLog::RETENTION_MONTHS)->subDay();
        $fresh = now()->subMonths(AppErrorLog::RETENTION_MONTHS)->addDay();

        AppErrorLog::query()->insert([
            ['message' => 'old', 'ip_address' => '10.0.0.1', 'created_at' => $old, 'updated_at' => $old],
            ['message' => 'fresh', 'ip_address' => '10.0.0.2', 'created_at' => $fresh, 'updated_at' => $fresh],
        ]);

        $this->artisan('model:prune', ['--model' => [AppErrorLog::class]])->assertSuccessful();

        $this->assertSame(['fresh'], AppErrorLog::query()->pluck('message')->all());
    }

    public function test_prune_is_scheduled(): void
    {
        $this->artisan('schedule:list')
            ->expectsOutputToContain('model:prune')
            ->assertSuccessful();
    }
}
