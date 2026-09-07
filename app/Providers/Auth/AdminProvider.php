<?php

namespace App\Providers\Auth;

use App\Core\Repository\User\UserRepository;
use App\Models\Users\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Hashing\HashManager;

class AdminProvider implements UserProvider
{
    /**
     * @param $identifier
     * @return User|Authenticatable|Builder|null
     */
    public function retrieveById($identifier): User|Builder|Authenticatable|null
    {
        return (new UserRepository())->findByAdminId($identifier);
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
     * @return User|false|Authenticatable|Builder|null
     */
    public function retrieveByCredentials(array $credentials): User|false|Builder|Authenticatable|null
    {
        if (is_null($username = $credentials['username'])) {
            return false;
        }

        $userRepository = new UserRepository();

        return $userRepository->findByAdmin($username);
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        /**
         * @var HashManager $hashManager
         */
        $hashManager = app(HashManager::class);

        if (is_null($plain = $credentials['password'])) {
            return false;
        }

        return $hashManager->check($plain, $user->getAuthPassword());
    }
}
