<?php

namespace App\Core\Repository\Enum;

use App\Models\Enums\EnumProductStatus;
use Illuminate\Database\Eloquent\Builder;

class ProductStatusRepository
{
    /**
     * @param int $id
     * @return Builder|EnumProductStatus
     */
    public function get(int $id): Builder|EnumProductStatus
    {
        return EnumProductStatus::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumProductStatus $enumProductStatus
     * @return void
     */
    public function save(EnumProductStatus $enumProductStatus): void
    {
        if (!$enumProductStatus->save()) {
            throw new \RuntimeException(__('client.Enum Product Status save error'));
        }
    }

    public function findAll()
    {
        return EnumProductStatus::query()
            ->selectRaw('enum_product_status.*')
            ->where(['enabled' => true])
            ->get();
    }
}
