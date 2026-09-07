<?php

namespace App\Core\Repository\Enum;

use App\Models\Enums\EnumAcademicDegree;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class AcademicDegreeRepository
{
    /**
     * @return Builder[]|Collection<int, EnumAcademicDegree>
     */
    public function findAll(): Collection|array
    {
        return EnumAcademicDegree::query()
            ->where('enabled', true)
            ->get();
    }

    /**
     * @param int $id
     * @return Builder|EnumAcademicDegree
     */
    public function get(int $id): Builder|EnumAcademicDegree
    {
        return EnumAcademicDegree::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumAcademicDegree $enumAcademicDegree
     * @return void
     */
    public function save(EnumAcademicDegree $enumAcademicDegree): void
    {
        if (!$enumAcademicDegree->save()) {
            throw new \RuntimeException(__('client.Enum Academic Degree save error'));
        }
    }
}
