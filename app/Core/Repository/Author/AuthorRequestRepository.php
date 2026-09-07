<?php

namespace App\Core\Repository\Author;

use App\Models\Authors\AuthorRequest;

class AuthorRequestRepository
{
    /**
     * @param AuthorRequest $authorRequest
     * @return void
     */
    public function save(AuthorRequest $authorRequest): void
    {
        if (!$authorRequest->save()) {
            throw new \RuntimeException(__('client.Save error author request'));
        }
    }

    public function get()
    {

    }
}
