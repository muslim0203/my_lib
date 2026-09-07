<?php

namespace App\Providers\Auth;

use App\Core\Repository\User\UserRepository;
use App\Models\Users\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;

class GoogleUserProvider implements UserProvider
{

    /**
     * @param $identifier
     * @return User|Authenticatable|Builder|null
     */
    public function retrieveById($identifier): User|Builder|Authenticatable|null
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

        if (empty($user->socialiteLogin)) {
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
            abort(401, __('client.Email is not empty'));
        }

        if (empty($credentials['provider_id'])) {
            abort(401, __('client.User is not empty provider id'));
        }

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = app(UserRepository::class);

        $user = $userRepository->findByGoogleUser($credentials['email']);

        if (empty($user)) {
            abort(401, __('client.User is not found'));
        }

        if (empty($socialiteUser = $user->socialiteLogin)) {
            abort(401, __('client.Socialite Login not found'));
        }

        if ($socialiteUser->getProviderId() !== $credentials['provider_id']) {
            abort(401, __('client.Social Login match not equal'));
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
        if (empty($credentials['email']) || empty($credentials['provider_id'])) {
            return false;
        }

        if ($user->isEmptySocialUser()) {
            return false;
        }

        if (!$user->isActive()) {
            return false;
        }

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = app(UserRepository::class);

        $user = $userRepository->findByGoogleUser($credentials['email']);

        if (empty($socialiteUser = $user->socialiteLogin)) {
            return false;
        }

        if ($socialiteUser->getProviderId() !== $credentials['provider_id']) {
            return false;
        }

        if (empty($user)) {
            return false;
        }

        return true;
    }
}
