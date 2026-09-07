<?php

namespace App\Core\Repository\User;

use App\Models\Users\UsersVerifyMailToken;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * @param string $token
     * @return UsersVerifyMailToken|Builder
     */
    public function getByToken(string $token): UsersVerifyMailToken|Builder
    {
        return UsersVerifyMailToken::query()
            ->where('token', $token)
            ->firstOrFail();
    }

    /**
     * @param int $user_id
     * @param string $token
     * @return Builder|UsersVerifyMailToken
     */
    public function getByTokenAndUserId(int $user_id, string $token): UsersVerifyMailToken|Builder
    {
        return UsersVerifyMailToken::query()
            ->where('token', $token)
            ->where('user_id', $user_id)
            ->firstOrFail();
    }

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
}
