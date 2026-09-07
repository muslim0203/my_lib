<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Admin panel uchun huquq va rollarni yaratadi.
 *
 * Ilgari bu seeder faqat `user`/`merchant`/`admin` nomli bo'sh huquqlarni
 * yaratardi va ular hech qayerda tekshirilmasdi: `employee_id` to'ldirilgan
 * har qanday foydalanuvchi paneldagi hamma amalni bajara olardi.
 *
 * Endi `web` guard uchun to'liq huquqlar to'plami va ikkita rol tuziladi:
 *   - `admin`     -> barcha `web` huquqlari;
 *   - `moderator` -> faqat ko'rish + tasdiqlash/rad etish
 *                    (o'chirish YO'Q, xodimlarni boshqarish YO'Q).
 *
 * Seeder idempotent: qayta ishga tushirilsa dublikat yaratmaydi va xato
 * bermaydi. Mavjud mail/google/phone guard huquqlari o'chirilmaydi.
 */
class PermissionSeeder extends Seeder
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';
    public const GUARD = 'web';

    /**
     * Admin panelidagi `web` guard huquqlari.
     *
     * @return string[]
     */
    public static function webPermissions(): array
    {
        return [
            // Ma'lumotnomalar (enum-*)
            'enum.view',
            'enum.manage',
            'enum.delete',

            // Kompaniya va unga bog'liq bo'limlar
            'company.view',
            'company.manage',
            'company.delete',

            // Savol-javob
            'question.view',
            'question.manage',
            'question.delete',

            // Mahsulotlar
            'product.view',
            'product.delete',

            // Bosh sahifa bannerlari
            'banner.view',
            'banner.manage',
            'banner.delete',

            // Hikmatli so'zlar
            'proverb.view',
            'proverb.manage',
            'proverb.delete',

            // Murojaat / mualliflik / vakolat moderatsiyasi
            'request.view',
            'request.moderate',

            // Xodimlarni boshqarish (o'z profilidan tashqari)
            'employee.manage',

            // Hisobotlar
            'report.view',
        ];
    }

    /**
     * `moderator` roliga beriladigan huquqlar.
     *
     * @return string[]
     */
    public static function moderatorPermissions(): array
    {
        return [
            'enum.view',
            'company.view',
            'question.view',
            'product.view',
            'banner.view',
            'proverb.view',
            'request.view',
            'request.moderate',
            'report.view',
        ];
    }

    /**
     * API guardlari uchun eski huquqlar. O'zgartirilmaydi.
     *
     * @return array[]
     */
    public static function list(): array
    {
        return [
            ['name' => 'user', 'guard_name' => 'mail'],
            ['name' => 'user', 'guard_name' => 'google'],
            ['name' => 'user', 'guard_name' => 'phone'],
            ['name' => 'merchant', 'guard_name' => 'mail'],
            ['name' => 'merchant', 'guard_name' => 'google'],
            ['name' => 'merchant', 'guard_name' => 'phone'],
            ['name' => 'admin', 'guard_name' => 'web'],
        ];
    }

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Eski (API) huquqlar saqlanib qoladi.
        foreach (self::list() as $item) {
            Permission::query()->firstOrCreate([
                'name' => $item['name'],
                'guard_name' => $item['guard_name'],
            ]);
        }

        // Admin panel huquqlari.
        foreach (self::webPermissions() as $name) {
            Permission::query()->firstOrCreate([
                'name' => $name,
                'guard_name' => self::GUARD,
            ]);
        }

        /** @var Role $admin */
        $admin = Role::query()->firstOrCreate([
            'name' => self::ROLE_ADMIN,
            'guard_name' => self::GUARD,
        ]);

        /** @var Role $moderator */
        $moderator = Role::query()->firstOrCreate([
            'name' => self::ROLE_MODERATOR,
            'guard_name' => self::GUARD,
        ]);

        // syncPermissions takroriy ishga tushirishda dublikat hosil qilmaydi.
        $admin->syncPermissions(
            Permission::query()->where('guard_name', self::GUARD)->get()
        );

        $moderator->syncPermissions(
            Permission::query()
                ->where('guard_name', self::GUARD)
                ->whereIn('name', self::moderatorPermissions())
                ->get()
        );

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->command?->info(
            "Huquqlar va rollar (admin, moderator) tayyor. Mavjud bazadagi "
            . "adminlarni bloklanib qolmasligi uchun bir marta ishga tushiring: "
            . "php artisan db:seed --class=Database\\Seeders\\RoleSeeder"
        );
    }
}
