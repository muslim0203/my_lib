<?php

namespace App\Core\Repository\User;

use App\Core\Enums\Users\UserStatusEnum;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    /**
     * @param string $email
     * @return Builder|User|null
     */
    public function findByGoogleUser(string $email): Builder|User|null
    {
        return User::query()
            ->from('users as u')
            ->where('u.email', $email)
            ->where('u.status', UserStatusEnum::_ACTIVE->value)
            ->whereNotNull('u.social_user_id')
            ->first();
    }

    /**
     * @param int $id
     * @return null|User|Builder
     */
    public function findByAdminId(int $id): null|User|Builder
    {
        return User::query()
            ->where('id', $id)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->whereNotNull('employee_id')
            ->first();
    }

    /**
     * @param string $username
     * @return Builder|User|null
     */
    public function findByAdmin(
        string $username
    ): Builder|User|null
    {
        return User::query()
            ->where('username', $username)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->whereNotNull('employee_id')
            ->first();
    }

    /**
     * @param int $id
     * @return Builder|User
     */
    public function get(int $id): User|Builder
    {
        return User::query()
            ->where('id', $id)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->firstOrFail();
    }

    /**
     * @param string $email
     * @return User|Builder|null
     */
    public function findByEmail(string $email): User|Builder|null
    {
        return User::query()
            ->where('email', $email)
            ->where('status', UserStatusEnum::_ACTIVE->value)
            ->whereNotNull('social_user_id')
            ->first();
    }

    public function findByUsername(string $username): mixed
    {
        return User::where('username', $username)
            ->first();
    }

    public function getById(int $id)
    {
        return User::findOrFail($id);
    }

    public function findAll()
    {
        return User::where('status', UserStatusEnum::_ACTIVE->value)->get();
    }

    /**
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException(__('client.User save error'));
        }
    }
}

