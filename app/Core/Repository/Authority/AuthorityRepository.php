<?php

namespace App\Core\Repository\Authority;

use App\Models\Authority\Authority;
use Illuminate\Database\Eloquent\Builder;

class AuthorityRepository
{
    /**
     * @param int $id
     * @return Builder|Authority
     */
    public function getById(int $id): Authority|Builder
    {
        return Authority::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param int $userId
     * @return Authority|Builder|null
     */
    public function findByUserId(int $userId): Authority|Builder|null
    {
        return Authority::query()
            ->where('user_id', $userId)
            ->where('enabled', true)
            ->first();
    }

    /**
     * @param Authority $authority
     * @param array $options
     * @return void
     */
    public function save(Authority $authority, array $options = []): void
    {
        if (!$authority->save($options)) {
            throw new \RuntimeException(__('client.Authority not saved.'));
        }
    }
}
