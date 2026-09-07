<?php

namespace App\Core\Repository\Product;

use App\Models\Enums\EnumProductStatus;

class ProductStatusRepository
{
    public function getCode(string $code)
    {
        return EnumProductStatus::query()
            ->where(['code' => $code])
            ->first();
    }
}
