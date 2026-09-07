<?php

namespace App\Core\Services\Author\Interfaces;

use App\Http\Requests\Authors\AuthorEditRequest;
use App\Http\Requests\Authors\ConfirmOrCancelRequest;

interface AuthorInterface
{
    public function edit(AuthorEditRequest $authorEditRequest): int;

    public function confirmOrCancel(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, bool $isConfirm = true): bool;
}
