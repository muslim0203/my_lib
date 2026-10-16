<?php

namespace App\Core\Services\User;

use App\Core\Enums\Users\UserStatusEnum;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;

/**
 * Xaridorning o'z akkauntini o'chirishi (Xaridorlar ofertasi 3.4-band,
 * Maxfiylik siyosati 5.2 va 7.1.3-bandlar).
 *
 * Qator jismonan o'chirilmaydi: to'lovlar va sotuvlar soliq/buxgalteriya
 * uchun saqlanishi shart. Buning o'rniga shaxsga doir ma'lumotlar
 * o'chiriladi (anonimlashtiriladi), kirish huquqlari yopiladi va akkaunt
 * `deleted` holatiga o'tkaziladi - bunday foydalanuvchi uchun JWT
 * provayderlari (EmailUserProvider, GoogleUserProvider) null qaytaradi,
 * ya'ni boshqa qurilmalardagi tokenlar ham ishlamay qoladi.
 *
 * Sotuvchilar (muallif yoki tashkilot arizasi bor) bu yo'l bilan
 * o'chirilmaydi: ular bilan tuzilgan shartnoma 13.2-band bo'yicha bekor
 * qilinadi, hujjatlari esa da'vo muddati davomida saqlanadi.
 */
class AccountDeletionService
{
    public function isSeller(User $user): bool
    {
        return DB::table('authors')->where('user_id', $user->getId())->exists()
            || DB::table('authority')->where('user_id', $user->getId())->exists();
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $userId = $user->getId();

            if ($user->social_user_id !== null) {
                DB::table('social_users')->where('id', $user->social_user_id)->update([
                    'first_name' => 'Deleted',
                    'last_name' => 'user',
                    'middle_name' => null,
                    'current_address' => null,
                    'description' => null,
                    'phone' => null,
                    'birth_date' => null,
                    'file_id' => null,
                    'file_name' => null,
                    'external_picture' => null,
                    'updated_at' => now(),
                ]);
            }

            DB::table('users')->where('id', $userId)->update([
                'username' => null,
                'email' => null,
                'phone' => null,
                'password' => null,
                'status' => UserStatusEnum::_DELETED->value,
                'updated_at' => now(),
            ]);

            DB::table('socialite_logins')->where('user_id', $userId)->delete();
            DB::table('users_verify_mail_tokens')->where('user_id', $userId)->delete();
            DB::table('user_interests')->where('user_id', $userId)->delete();
            DB::table('link_author_subscribers')->where('subscriber_id', $userId)->delete();
            DB::table('notifications')->where('user_id', $userId)->delete();

            // Buyurtmalar hisob uchun qoladi, lekin kirish yopiladi.
            DB::table('products_orders')->where('customer_id', $userId)
                ->update(['enabled' => false, 'updated_at' => now()]);
        });
    }
}
