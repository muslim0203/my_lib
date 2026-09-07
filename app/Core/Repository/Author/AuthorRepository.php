<?php

namespace App\Core\Repository\Author;

use App\Models\Authors\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuthorRepository
{
    /**
     * @param Author $author
     * @return void
     */
    public function save(Author $author): void
    {
        if (!$author->save()) {
            throw new \RuntimeException(__('client.Author save error'));
        }
    }

    /**
     * @param int $id
     * @return Builder|Author
     */
    public function get(int $id): Author|Builder
    {
        return Author::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param int $user_id
     * @return Builder|Author|null
     */
    public function findByUserId(int $user_id): Author|Builder|null
    {
        return Author::query()
            ->where('user_id', $user_id)
            ->where('enabled', true)
            ->first();
    }
}
