<?php

namespace App\Core\Repository\User;

use App\Core\Enums\Auth\ProvidersEnum;
use App\Models\Users\SocialiteLogin;
use Illuminate\Database\Eloquent\Builder;

class SocialiteLoginRepository
{
    /**
     * @param int $userId
     * @param string $providerId
     * @param string $provider
     * @return SocialiteLogin|Builder|null
     */
    public function findByUserIdAndProviderId(int $userId, string $providerId, string $provider = ProvidersEnum::_GOOGLE->value): null|SocialiteLogin|Builder
    {
        return SocialiteLogin::query()
            ->where('user_id', $userId)
            ->where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }

    /**
     * @param int $user_id
     * @param string $provider
     * @return Builder|SocialiteLogin|null
     */
    public function findByUserIdAndProvider(int $user_id, string $provider = ProvidersEnum::_GOOGLE->value): Builder|SocialiteLogin|null
    {
        return SocialiteLogin::query()
            ->where('user_id', $user_id)
            ->where('provider', $provider)
            ->first();
    }

    /**
     * @param int $user_id
     * @param string $provider
     * @return Builder|SocialiteLogin
     */
    public function getByUserIdAndProvider(int $user_id, string $provider = ProvidersEnum::_GOOGLE->value): Builder|SocialiteLogin
    {
        return SocialiteLogin::query()
            ->where('user_id', $user_id)
            ->where('provider', $provider)
            ->firstOrFail();
    }

    /**
     * @param int $user_id
     * @param string $google_id
     * @return Builder|SocialiteLogin
     */
    public function getByUserIdAndGoogleId(int $user_id, string $google_id): Builder|SocialiteLogin
    {
        return SocialiteLogin::query()
            ->where('user_id', $user_id)
            ->where('provider', ProvidersEnum::_GOOGLE->value)
            ->where('provider_id', $google_id)
            ->firstOrFail();
    }

    /**
     * @param SocialiteLogin $socialiteLogin
     * @return void
     */
    public function save(SocialiteLogin $socialiteLogin): void
    {
        if (!$socialiteLogin->save()) {
            throw new \RuntimeException(__('client.User save error'));
        }
    }
}
