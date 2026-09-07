<?php

namespace App\Core\Services\Authority\Contract;

use App\Http\Requests\Authors\ConfirmOrCancelRequest;

interface AuthorityContract
{
    public function confirmOrCancel(ConfirmOrCancelRequest $confirmOrCancelRequest, int $id, bool $isConfirm = true): bool;

}
