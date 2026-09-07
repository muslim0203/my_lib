<?php

namespace App\Core\Repository\User;

use App\Models\Users\SocialUser;

class SocialUserRepository
{
    /**
     * @param SocialUser $socialUser
     * @return void
     */
    public function save(SocialUser $socialUser): void
    {
        if (!$socialUser->save()) {
            throw new \RuntimeException(__('client.Social users save error'));
        }
    }
}
