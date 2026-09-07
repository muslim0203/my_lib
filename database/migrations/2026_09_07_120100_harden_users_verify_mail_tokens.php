<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * OTP kodini xavfsiz saqlash uchun ustunlar.
 *
 * Mavjud `token` ustuni (varchar 25) kodni ochiq matnda saqlagan va
 * uning turini o'zgartirish uchun doctrine/dbal kerak bo'lar edi. Shuning
 * uchun yangi `token_hash` ustuni qo'shiladi; eski ustun tegilmaydi va
 * unga endi hech qanday sir yozilmaydi.
 *
 * `attempts` - bitta kod uchun noto'g'ri urinishlar soni.
 * `used_at` - kod bir marta ishlatilgani (qayta ishlatib bo'lmaydi).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users_verify_mail_tokens')) {
            return;
        }

        Schema::table('users_verify_mail_tokens', function (Blueprint $table) {
            if (!Schema::hasColumn('users_verify_mail_tokens', 'token_hash')) {
                $table->string('token_hash', 255)->nullable();
            }

            if (!Schema::hasColumn('users_verify_mail_tokens', 'attempts')) {
                $table->unsignedSmallInteger('attempts')->default(0);
            }

            if (!Schema::hasColumn('users_verify_mail_tokens', 'used_at')) {
                $table->timestamp('used_at')->nullable();
            }
        });

        // Eski ochiq matnli kodlar endi ishlamaydi va saqlanishi ham
        // shart emas. Ular o'chiriladi; foydalanuvchi yangi kod so'raydi.
        DB::table('users_verify_mail_tokens')->update([
            'token' => '',
            'enabled' => false,
        ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('users_verify_mail_tokens')) {
            return;
        }

        Schema::table('users_verify_mail_tokens', function (Blueprint $table) {
            foreach (['token_hash', 'attempts', 'used_at'] as $column) {
                if (Schema::hasColumn('users_verify_mail_tokens', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
