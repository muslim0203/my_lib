<?php

namespace App\Core\Repository\User;

use App\Models\Users\UsersVerifyMailToken;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersVerifyTokenMailRepository
{
    /**
     * @param UsersVerifyMailToken $usersVerifyMailToken
     * @return void
     */
    public function save(UsersVerifyMailToken $usersVerifyMailToken): void
    {
        if (!$usersVerifyMailToken->save()) {
            throw new \RuntimeException(__('client.Users verify mail token save error'));
        }
    }

    // getByToken() va getByTokenAndUserId() olib tashlandi: ular kodni
    // ochiq matnda qidirar edi. Kod endi hash bilan saqlanadi va faqat
    // consume() orqali tekshiriladi.

    /**
     * @param int $user_id
     * @return Builder|UsersVerifyMailToken|null
     */
    public function findByUser(int $user_id): UsersVerifyMailToken|Builder|null
    {
        return UsersVerifyMailToken::query()
            ->where('user_id', $user_id)
            ->first();
    }

    /**
     * Kodni bir martalik tarzda tekshiradi va iste'mol qiladi.
     *
     * Tekshiruv, urinishlar hisobi va iste'mol bitta tranzaksiya va
     * qator qulfi ostida bajariladi, shuning uchun bir vaqtda kelgan
     * ikkita so'rov bir kodni ikki marta ishlata olmaydi.
     *
     * @param int $user_id
     * @param string $code
     * @param int $maxAttempts
     * @return bool
     */
    public function consume(int $user_id, string $code, int $maxAttempts): bool
    {
        return DB::transaction(function () use ($user_id, $code, $maxAttempts) {
            /**
             * @var UsersVerifyMailToken|null $token
             */
            $token = UsersVerifyMailToken::query()
                ->where('user_id', $user_id)
                ->lockForUpdate()
                ->first();

            if (empty($token)
                || !$token->isEnable()
                || $token->isConsumed()
                || $token->isExpired()
                || empty($token->getTokenHash())
            ) {
                return false;
            }

            if ($token->getAttempts() >= $maxAttempts) {
                $token->setEnabled(false);
                $token->save();

                return false;
            }

            // Saqlangan qiymat parol hash'i, shuning uchun tekshiruv
            // hash verifikatori bilan bajariladi. Yuborilgan kodni qayta
            // hash qilib solishtirish noto'g'ri bo'lar edi.
            if (!Hash::check($code, $token->getTokenHash())) {
                $token->setAttempts($token->getAttempts() + 1);

                if ($token->getAttempts() >= $maxAttempts) {
                    $token->setEnabled(false);
                }

                $token->save();

                return false;
            }

            $token->setEnabled(false);
            $token->setUsedAt(now()->format('Y-m-d H:i:s'));
            $token->save();

            return true;
        });
    }
}
