<?php

namespace App\Core\Repository\Product;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Products\ProductPriceType;
use Illuminate\Database\Eloquent\Builder;

class ProductPriceTypeRepository
{
    /**
     * @param int $id
     * @return Builder|ProductPriceType
     */
    public function getById(int $id): ProductPriceType|Builder
    {
        return ProductPriceType::query()->where('id', $id)->firstOrFail();
    }

    /**
     * @param ProductPriceType $productPriceType
     * @param array $options
     * @return void
     */
    public function save(ProductPriceType $productPriceType, array $options = []): void
    {
        if (!$productPriceType->save($options)) {
            throw new \RuntimeException(__('Saving error.'));
        }
    }

    /**
     * @return array
     */
    public function findAll(): array
    {
        return ProductPriceType::query()
            ->where('enabled', true)
            ->get()
            ->all();
    }
}
