<?php

namespace App\Core\Repository\Product;

use App\Models\Enums\EnumProductTag;

class ProductTagRepository
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        return EnumProductTag::query()
            ->where('enabled', true)
            ->get()
            ->all();
    }
}
