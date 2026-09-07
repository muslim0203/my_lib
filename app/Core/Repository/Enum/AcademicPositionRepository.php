<?php

namespace App\Core\Repository\Enum;

use App\Models\Enums\EnumAcademicPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AcademicPositionRepository
{
    /**
     * @return Builder[]|Collection<int, EnumAcademicPosition>
     */
    public function findAll(): Collection|array
    {
        return EnumAcademicPosition::query()
            ->where('enabled', true)
            ->get();
    }

    /**
     * @param int $id
     * @return Builder|EnumAcademicPosition
     */
    public function get(int $id): Builder|EnumAcademicPosition
    {
        return EnumAcademicPosition::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumAcademicPosition $enumAcademicPosition
     * @return void
     */
    public function save(EnumAcademicPosition $enumAcademicPosition): void
    {
        if (!$enumAcademicPosition->save()) {
            throw new \RuntimeException(__('client.Enum Academic Position save error'));
        }
    }
}
