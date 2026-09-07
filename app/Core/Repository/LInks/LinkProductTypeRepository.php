<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkProductType;

class LinkProductTypeRepository
{
    /**
     * @param LinkProductType $linkProductType
     * @param array $options
     * @return void
     */
    public function save(LinkProductType $linkProductType, array $options = []): void
    {
        if (!$linkProductType->save($options)) {
            throw new \RuntimeException(__('client.Save error'));
        }
    }

    /**
     * @param array $typeIds
     * @param int $productId
     * @return array
     */
    public function findAllById(array $typeIds, int $productId): array
    {
        return LinkProductType::query()
            ->whereIn('type_id', $typeIds)
            ->whereNot('product_id', $productId)
            ->get()
            ->all();
    }
}
