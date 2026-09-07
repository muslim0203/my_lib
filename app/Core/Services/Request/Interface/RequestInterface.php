<?php

namespace App\Core\Services\Request\Interface;

use App\Http\Requests\Requests\ConfirmOrCancelRequest;

interface RequestInterface
{
    public function confirmRequest(ConfirmOrCancelRequest $confirmOrCancelRequest,int $id);
    public function rejectRequest(ConfirmOrCancelRequest $confirmOrCancelRequest,int $id);
    public function saveLinkCategories(int $id,array $categories);
    public function saveLinkTags(int $id,array $tags);
    public function saveLinkGenres(int $id,array $genres);
    public function saveDiscount(int $id,int $discount);
    public function saveAuthorProfileFile(int $id,array $data);
    public function saveAuthorityProfileFile(int $id,array $data);
    public function saveLinkTypes(int $id,array $types);
}
