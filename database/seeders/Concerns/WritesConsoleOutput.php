<?php

namespace Database\Seeders\Concerns;

use Illuminate\Console\Command;

/**
 * Seeder xabarlarini konsolga xavfsiz yozish.
 *
 * `Illuminate\Database\Seeder::$command` docblokda non-nullable deb
 * e'lon qilingan, lekin u tur bildirilmagan xususiyat va konsoldan
 * tashqarida ishga tushirilganda `null` bo'lib qoladi (framework'ning
 * o'zi ham `isset()` bilan tekshiradi). Shu sababli qiymat aniq
 * nullable sifatida o'qiladi.
 */
trait WritesConsoleOutput
{
    protected function writeInfo(string $message): void
    {
        /** @var Command|null $command */
        $command = $this->command ?? null;

        $command?->info($message);
    }
}
