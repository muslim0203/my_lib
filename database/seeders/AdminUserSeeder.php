<?php

namespace Database\Seeders;

use App\Core\Helpers\Transaction;
use App\Models\Users\Employee;
use App\Models\Users\User;
use Illuminate\Database\Seeder;

/**
 * Birinchi admin hisobini yaratadi.
 *
 * Ilgari bu yerda ochiq matnli parol (`Qwerty123$`) va tasodifiy email
 * bo'lgan, ya'ni har bir o'rnatish bir xil, hammaga ma'lum parol bilan
 * ochilardi. Endi parol majburiy ravishda konfiguratsiyadan olinadi va
 * u yo'q bo'lsa seeder to'xtaydi.
 *
 * Mavjud admin hech qachon qayta yozilmaydi: takroriy ishga tushirish
 * paroli yoki huquqlarni tiklamaydi.
 */
class AdminUserSeeder extends Seeder
{
    public function run(Transaction $transaction): void
    {
        // config:cache yoqilganda env() null qaytaradi, shuning uchun
        // qiymatlar konfiguratsiya orqali o'qiladi.
        $username = (string)config('auth.admin_initial.username', 'admin');
        $email = (string)config('auth.admin_initial.email', '');
        $password = (string)config('auth.admin_initial.password', '');

        if (User::query()->where('username', $username)->exists()) {
            $this->command?->info(
                "Admin '{$username}' allaqachon mavjud. Hech narsa o'zgartirilmadi."
            );

            return;
        }

        if ($password === '') {
            // Fail closed: parolsiz admin yaratilmaydi.
            throw new \RuntimeException(
                'ADMIN_INITIAL_PASSWORD sozlanmagan. Birinchi adminni yaratish uchun '
                . 'ADMIN_INITIAL_PASSWORD (va ixtiyoriy ADMIN_INITIAL_USERNAME, '
                . 'ADMIN_INITIAL_EMAIL) qiymatlarini muhitda bering.'
            );
        }

        if (mb_strlen($password) < 12) {
            throw new \RuntimeException(
                'ADMIN_INITIAL_PASSWORD kamida 12 belgidan iborat bo\'lishi kerak.'
            );
        }

        if ($email === '') {
            throw new \RuntimeException('ADMIN_INITIAL_EMAIL sozlanmagan.');
        }

        $transaction->wrap(function () use ($username, $email, $password) {
            // Employee yozuvi faqat admin haqiqatan yaratilayotganda
            // qo'shiladi. Ilgari u har bir ishga tushirishda yaratilib,
            // yetim qatorlar to'planardi.
            $employee = Employee::factory()->create();

            $user = new User();
            $user->fill([
                'username' => $username,
                'password' => $password,
                'email' => $email,
            ]);
            $user->setEmployeeId($employee->getKey());
            $user->save();

            // Rolsiz admin panelga kira olmaydi (Gate::before faqat
            // `admin` roliga hamma narsani beradi), shuning uchun rol
            // aynan shu yerda beriladi.
            $user->assignRole(PermissionSeeder::ROLE_ADMIN);
        });

        $this->command?->info("Admin '{$username}' yaratildi.");
    }
}
