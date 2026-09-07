<?php

namespace App\Core\Repository\Enum;

use App\Models\Enums\EnumProductType;
use Illuminate\Database\Eloquent\Builder;

class ProductTypeRepository
{
    /**
     * @param int $id
     * @return Builder|EnumProductType
     */
    public function get(int $id): Builder|EnumProductType
    {
        return EnumProductType::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumProductType $enumProductType
     * @return void
     */
    public function save(EnumProductType $enumProductType): void
    {
        if (!$enumProductType->save()) {
            throw new \RuntimeException(__('client.Enum Product Type save error'));
        }
    }

    public function findAll()
    {
        return EnumProductType::query()
            ->selectRaw('enum_product_types.*')
            ->where(['enabled' => true])
            ->get();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return EnumProductType::query()
            ->where('id', $id)
            ->where('enabled', true)
            ->exists();
    }
}
