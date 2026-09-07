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
        if (empty($credentials['email'])) {
            abort(403, __('client.Credentials must not empty'));
        }

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = app(UserRepository::class);


        if (empty($user = $userRepository->findByEmail($credentials['email']))) {
            abort(401, __('client.Unauthorized. Email is error'));
        }

        if (!$user->isActive()) {
            abort(401, __('client.Unauthorized. User is not active'));
        }

        if ($user->isEmptySocialUser()) {
            abort(401, __('client.Unauthorized. Social user is not found'));
        }

        if (empty($credentials['code'])) {
            abort(401, __('client.Unauthorized. Credentials must not empty'));
        }

        /**
         * @var UsersVerifyTokenMailRepository $userVerifyMailTokenRepository
         */
        $userVerifyMailTokenRepository = app(UsersVerifyTokenMailRepository::class);

        $userVerifyMailToken = $userVerifyMailTokenRepository->getByTokenAndUserId($user->getId(), $credentials['code']);

        if (!$userVerifyMailToken->isEnable()) {
            abort(401, __('client.Unauthorized. Code is disabled'));
        }

        if (!$userVerifyMailToken->isExpiredToken()) {
            abort(401, __('client.Unauthorized. Code is expired'));
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
        if (empty($credentials['email'])) {
            return false;
        }

        if (!$user->isActive()) {
            return false;
        }

        if ($user->isEmptySocialUser()) {
            return false;
        }

        if (empty($credentials['code'])) {
            return false;
        }

        /**
         * @var User $user
         * @var UsersVerifyMailToken $verifyTokenMail
         */
        $verifyTokenMail = $user->verifyMailToken;

        if (empty($verifyTokenMail)) {
            return false;
        }

        if (!$verifyTokenMail->isEnable()) {
            return false;
        }

        if (!$verifyTokenMail->isExpiredToken()) {
            return false;
        }

        return $credentials['code'] == $verifyTokenMail->getToken();
    }
}
