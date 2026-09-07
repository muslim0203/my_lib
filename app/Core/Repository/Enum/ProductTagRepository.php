<?php

namespace App\Core\Repository\Enum;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Enums\EnumProductTag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductTagRepository
{
    /**
     * @param int $id
     * @return Builder|EnumProductTag
     */
    public function get(int $id): Builder|EnumProductTag
    {
        return EnumProductTag::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumProductTag $enumProductTag
     * @return void
     */
    public function save(EnumProductTag $enumProductTag): void
    {
        if (!$enumProductTag->save()) {
            throw new \RuntimeException(__('client.Enum Product Tags save error'));
        }
    }

    public function findAll()
    {
        return EnumProductTag::query()
            ->select(['id', LanguageHelper::getName().' AS name','enabled'])
            ->where(['enabled' => true])
            ->get();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return EnumProductTag::query()
            ->where('id', $id)
            ->exists();
    }

    public function getTagList(array $ids): Collection|array
    {
        return EnumProductTag::query()->select('id',LanguageHelper::getName().' AS name')->whereIn('id', $ids)->get();
    }
}
