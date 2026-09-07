<?php

namespace App\Core\Repository\Product;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Core\Helpers\Transaction;
use App\Models\Enums\EnumProductGenre;

class ProductGenreRepository
{
    /**
     * @return array
     */
    public function findAll(): array
    {
        return EnumProductGenre::query()
            ->select(['id', LanguageHelper::getName() . ' AS name', 'enabled'])
            ->where('enabled', true)
            ->get()
            ->all();
    }

    /**
     * @return array
     */
    public function findAllBySelect(): array
    {
        return EnumProductGenre::query()
            ->where('enabled', true)
            ->get()
            ->all();
    }
}
