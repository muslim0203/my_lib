<?php

namespace App\Core\Repository\Enum;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Enums\EnumCategories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CategoriesRepository
{
    /**
     * @param int $id
     * @return Builder|EnumCategories
     */
    public function get(int $id): Builder|EnumCategories
    {
        return EnumCategories::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumCategories $enumCategories
     * @return void
     */
    public function save(EnumCategories $enumCategories): void
    {
        if (!$enumCategories->save()) {
            throw new \RuntimeException(__('client.Enums Categories save error'));
        }
    }

    public function findList(): array
    {
        return $this->generateCategories(EnumCategories::query()
            ->where(['enabled' => true])
            ->orderBy('sort')
            ->get());
    }

    public function generateCategories($categories, $parentId = 0): array
    {
        $array = [];
        foreach ($categories as $category) {
            /**
             * @var EnumCategories $category
             */
            if ($category->parent_id == $parentId) {
                $children = $this->generateCategories($categories, $category->id);

                $array[] = [
                    'id' => $category->id,
                    'text' => $category->{LanguageHelper::getName()},
                    'icon' => $category->icon,
                    'parent_id' => $category->getParentId(),
                    'child' => !empty($children) ? $children : null,
                    'has_extra_column_require' => $category->isHasExtraColumnRequire(),
                ];
            }
        }

        return $array;
    }

    public function findAll()
    {
        return EnumCategories::query()
            ->select(['id', LanguageHelper::getName() . ' AS name', 'parent_id', 'icon', 'sort'])
            ->where(['enabled' => true])
            ->get();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return EnumCategories::query()
            ->where('id', $id)
            ->exists();
    }

    public function getCategoryList(array $ids): Collection|array
    {
        return EnumCategories::query()->select('id', LanguageHelper::getName() . ' AS name')->whereIn('id', $ids)->get();
    }

    /**
     * @param array $ids
     * @return array|EnumCategories[]
     */
    public function findByIds(array $ids): array
    {
        return EnumCategories::query()
            ->whereIn('id', $ids)
            ->get()
            ->all();
    }

    /**
     * @param array $ids
     * @return bool
     */
    public function existsHasExtraColumnRequire(array $ids): bool
    {
        return EnumCategories::query()
            ->whereIn('id', $ids)
            ->where('has_extra_column_require', true)
            ->exists();
    }

}
