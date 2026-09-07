<?php

namespace App\Core\Repository\Author;

use App\Models\Authors\Author;
use App\Models\Authors\AuthorProfileFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AuthorProfileFileRepository
{
    /**
     * @param AuthorProfileFile $authorProfileFile
     * @return void
     */
    public function save(AuthorProfileFile $authorProfileFile): void
    {
        if (!$authorProfileFile->save()) {
            throw new \RuntimeException(__('client.Save error profile file'));
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
            ->where('enabled', true)
            ->firstOrFail();
    }

    /**
     * @param int $authorId
     * @return Builder|AuthorProfileFile|null
     */
    public function findByAuthorId(int $authorId): AuthorProfileFile|Builder|null
    {
        return AuthorProfileFile::query()
            ->where('author_id', $authorId)
            ->where('enabled', true)
            ->first();
    }
}
