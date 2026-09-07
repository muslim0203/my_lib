<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkProductCategories;

class LinkProductCategoryRepository
{
    /**
     * @param LinkProductCategories $linkProductCategories
     * @param array $options
     * @return void
     */
    public function save(LinkProductCategories $linkProductCategories, array $options = []): void
    {
        if (!$linkProductCategories->save($options)) {
            throw new \RuntimeException(__('client.Save error link product categories'));
        }
    }

    /**
     * @param int $product_id
     * @return array
     */
    public function findAllByProductId(int $product_id): array
    {
        return LinkProductCategories::query()
            ->where('product_id', $product_id)
            ->where('enabled', true)
            ->get()
            ->all();
    }
}
