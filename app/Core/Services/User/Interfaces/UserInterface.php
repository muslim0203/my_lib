<?php

namespace App\Core\Services\User\Interfaces;

use App\Http\Requests\User\SelectInterestRequest;
use App\Http\Requests\User\UserEditRequest;
use App\Http\Resources\User\UserViewResource;

interface UserInterface
{
    public function list(): UserViewResource;

    public function edit(UserEditRequest $userEditRequest): int;

    public function selectInterest(SelectInterestRequest $selectInterestRequest): bool;

    public function interestList(): array;
}
