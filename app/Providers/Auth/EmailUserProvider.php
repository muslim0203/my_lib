<?php

namespace App\Providers\Auth;

use App\Core\Repository\User\UserRepository;
use App\Core\Repository\User\UsersVerifyTokenMailRepository;
use App\Models\Users\User;
use App\Models\Users\UsersVerifyMailToken;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class EmailUserProvider implements UserProvider
{
    /**
     * @param $identifier
     * @return User|Builder|null
     */
    public function retrieveById($identifier): User|Builder|null
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = app(UserRepository::class);

        $user = $userRepository->get($identifier);

        if (!$user->isActive()) {
            return null;
        }

        if ($user->isEmptySocialUser()) {
            return null;
        }

        return $user;
    }

    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * @param array $credentials
     * @return User|Authenticatable|Builder|null
     */
    public function retrieveByCredentials(array $credentials): User|Builder|Authenticatable|null
    {
        // Bu bosqichda hech qanday farqlovchi xato qaytarilmaydi.
        // Ilgari har bir holat uchun alohida xabar berilardi va bu
        // qaysi pochta ro'yxatdan o'tganini, qaysi kod amalda ekanini
        // oshkor qilardi. Kod to'g'riligi validateCredentials ichida,
        // urinishlar hisobi bilan birga tekshiriladi.
        if (empty($credentials['email']) || empty($credentials['code'])) {
            return null;
        }

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = app(UserRepository::class);

        $user = $userRepository->findByEmail($credentials['email']);

        if (empty($user)) {
            return null;
        }

        if (!$user->isActive()) {
            return null;
        }

        if ($user->isEmptySocialUser()) {
            return null;
        }

        return $user;
    }

    /**
     * @param Authenticatable|User $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        if (empty($credentials['email']) || empty($credentials['code'])) {
            return false;
        }

        /**
         * @var User $user
         */
        if (!$user->isActive() || $user->isEmptySocialUser()) {
            return false;
        }

        /**
         * @var UsersVerifyTokenMailRepository $userVerifyMailTokenRepository
         */
        $userVerifyMailTokenRepository = app(UsersVerifyTokenMailRepository::class);

        // Tekshiruv, urinishlar hisobi va bir martalik iste'mol qator
        // qulfi ostida, bitta amalda bajariladi.
        return $userVerifyMailTokenRepository->consume(
            $user->getId(),
            (string)$credentials['code'],
            (int)config('auth.mail_code_max_attempts', 5)
        );
    }
}
