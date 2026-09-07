<?php

namespace App\Core\Repository\Enum;

use App\Models\Enums\EnumActivityType;
use Illuminate\Database\Eloquent\Builder;

class ActivityTypeRepository
{
    /**
     * @param int $id
     * @return Builder|EnumActivityType
     */
    public function get(int $id): EnumActivityType|Builder
    {
        return EnumActivityType::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @return array|EnumActivityType[]
     */
    public function findAll(): array
    {
        return EnumActivityType::query()
            ->get()
            ->all();
    }
}
