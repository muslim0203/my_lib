<?php

namespace App\Core\Repository\Author;

use App\Models\Authors\AuthorComments;

class AuthorCommentRepository
{
    /**
     * @param AuthorComments $authorComment
     * @param array $options
     * @return void
     */
    public function save(AuthorComments $authorComment, array $options = []): void
    {
        if (!$authorComment->save($options)) {
            throw new \RuntimeException(__('client.Save error'));
        }
    }
}
