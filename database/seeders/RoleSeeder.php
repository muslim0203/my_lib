<?php

namespace Database\Seeders;

use App\Models\Users\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

/**
 * BIR MARTALIK O'TISH (migration) QADAMI - LOCKOUT'NING OLDINI OLADI.
 *
 * Ilgari "admin" tushunchasi faqat `users.employee_id IS NOT NULL` orqali
 * ifodalangan va hech qanday huquq tekshirilmagan. Endi marshrutlar
 * `can:*` middleware bilan himoyalangan, ya'ni roli yo'q eski adminlar
 * paneldan butunlay chiqib qolgan bo'lardi.
 *
 * Bu seeder aynan shu holatni tuzatadi: `employee_id` to'ldirilgan va
 * hozircha HECH QANDAY roli bo'lmagan foydalanuvchilarga `admin` rolini
 * beradi. Roli allaqachon bor foydalanuvchi (masalan, ataylab `moderator`
 * qilinganlar) TEGILMAYDI - shuning uchun seederni qayta ishga tushirish
 * xavfsiz va u hech kimni sezdirmasdan admin qilib yubormaydi.
 *
 * Ishga tushirish (deploy paytida bir marta):
 *
 *   php artisan db:seed --class=Database\Seeders\RoleSeeder
 *
 * DIQQAT: bu seeder ataylab DatabaseSeeder ro'yxatiga qo'shilmagan -
 * u aniq, ko'rinadigan, qo'lda bajariladigan qadam bo'lishi kerak.
 */
class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $this->callOnce(PermissionSeeder::class);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $granted = 0;
        $skipped = 0;

        User::query()
            ->whereNotNull('employee_id')
            ->orderBy('id')
            ->chunkById(200, function ($users) use (&$granted, &$skipped) {
                /** @var User $user */
                foreach ($users as $user) {
                    if ($user->roles()->exists()) {
                        $skipped++;
                        continue;
                    }

                    $user->assignRole(PermissionSeeder::ROLE_ADMIN);
                    $granted++;
                }
            });

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info(
            "RoleSeeder: {$granted} ta xodim-foydalanuvchiga `admin` roli berildi, "
            . "{$skipped} tasida rol allaqachon bor edi (tegilmadi)."
        );
    }
}
