<?php

namespace App\Core\Repository\Product;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Enums\EnumProductType;
use Illuminate\Database\Eloquent\Collection;

class ProductTypeRepository
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        return EnumProductType::query()
            ->where('enabled', true)
            ->get()
            ->all();
    }

    public function getTypeList(array $ids = null): Collection|array|null
    {
        if (!empty($ids)) {
            return EnumProductType::query()->select('id',LanguageHelper::getName().' AS name')->whereIn('id', $ids)->get();
        }
        return null;
    }
}
