<?php

namespace App\Core\Repository\User;

use App\Models\Users\UserInterest;

class UserInterestRepository
{
    /**
     * @param UserInterest $userInterest
     * @param array $options
     * @return void
     */
    public function save(UserInterest $userInterest, array $options = []): void
    {
        if (!$userInterest->save($options)) {
            throw new \RuntimeException(__('client.Save error'));
        }
    }

    /**
     * @param int $user_id
     * @return array
     */
    public function findAllByUser(int $user_id): array
    {
        return UserInterest::query()
            ->where('user_id', $user_id)
            ->where('enabled', true)
            ->get()
            ->all();
    }

    /**
     * @param int $user_id
     * @return void
     */
    public function removeByUser(int $user_id): void
    {
        UserInterest::query()
            ->where('user_id', $user_id)
            ->delete();
    }
}
