<?php

namespace App\Core\Repository\Enum;

use App\Models\Enums\EnumEducationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EducationTypeRepository
{
    /**
     * @param int $id
     * @return Builder|EnumEducationType
     */
    public function get(int $id): Builder|EnumEducationType
    {
        return EnumEducationType::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumEducationType $enumEducationType
     * @return void
     */
    public function save(EnumEducationType $enumEducationType): void
    {
        if (!$enumEducationType->save()) {
            throw new \RuntimeException(__('client.Education Type save error'));
        }
    }

    /**
     * @return Builder[]|Collection<int, EnumEducationType>
     */
    public function findAll(): Collection|array
    {
        return EnumEducationType::query()
            ->where('enabled', true)
            ->get();
    }
}
