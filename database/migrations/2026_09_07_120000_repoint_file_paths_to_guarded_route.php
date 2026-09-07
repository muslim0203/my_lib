<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Eski fayl yozuvlari `/storage/<nom>` havolasini saqlagan. Bu havola
 * public disk symlinkiga tayanadi va pullik manba fayllarni ham
 * avtorizatsiyasiz ochib qo'yadi.
 *
 * Migratsiya faqat `files.path` ustunini himoyalangan `file-view`
 * marshrutiga qaratadi. Fayllarning o'zi ko'chirilmaydi va o'chirilmaydi:
 * o'qishda eski disklar ham tekshiriladi (filesystems.legacy_read_disks).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('files')) {
            return;
        }

        $prefix = rtrim((string)config('filesystems.public_url_prefix', '/api/file-view'), '/');

        DB::table('files')
            ->where(function ($query) {
                $query->whereNull('path')
                    ->orWhere('path', 'like', '/storage%');
            })
            ->update(['path' => $prefix]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('files')) {
            return;
        }

        $prefix = rtrim((string)config('filesystems.public_url_prefix', '/api/file-view'), '/');

        DB::table('files')
            ->where('path', $prefix)
            ->update(['path' => '/storage']);
    }
};
