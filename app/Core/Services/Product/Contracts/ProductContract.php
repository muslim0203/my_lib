<?php

namespace App\Core\Services\Product\Contracts;

use App\Http\Requests\Products\ProductCreateRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Http\FormRequest;

interface ProductContract
{
    public function createRequest(ProductCreateRequest $productCreateRequest, ?int $id = null): int;

    public function assessment(FormRequest $formRequest, int $id): bool;

    public function comment(FormRequest $formRequest, int $id): bool;

    public function commentList(FormRequest $formRequest, int $id);

    public function assessmentList(int $id);

    public function similarProducts(int $productId): array;

    public function favoriteProducts(FormRequest $formRequest): array;

    public function buy(int $id): void;

    public function myList(): LengthAwarePaginator;
}
