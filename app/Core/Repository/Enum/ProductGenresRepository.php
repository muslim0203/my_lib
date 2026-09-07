<?php

namespace App\Core\Repository\Enum;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Enums\EnumProductGenre;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ProductGenresRepository
{
    /**
     * @param int $id
     * @return Builder|EnumProductGenre
     */
    public function get(int $id): Builder|EnumProductGenre
    {
        return EnumProductGenre::query()
            ->where('id', $id)
            ->firstOrFail();
    }

    /**
     * @param EnumProductGenre $enumProductGenre
     * @return void
     */
    public function save(EnumProductGenre $enumProductGenre): void
    {
        if (!$enumProductGenre->save()) {
            throw new \RuntimeException(__('client.Enum Language save error'));
        }
    }

    public function findAll()
    {
        return EnumProductGenre::query()
            ->selectRaw('enum_product_genres.*')
            ->where(['enabled' => true])
            ->get();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function exists(int $id): bool
    {
        return EnumProductGenre::query()
            ->where('id', $id)
            ->exists();
    }
    public function getGenreList(array $ids): Collection|array
    {
        return EnumProductGenre::query()->select('id',LanguageHelper::getName().' AS name')->whereIn('id', $ids)->get();
    }
}
