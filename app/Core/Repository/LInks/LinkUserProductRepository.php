<?php

namespace App\Core\Repository\LInks;

use App\Models\Links\LinkUserProduct;
use Illuminate\Database\Eloquent\Collection;

class LinkUserProductRepository
{
    /**
     * @param LinkUserProduct $linkUserProduct
     * @param array $params
     * @return void
     */
    public function save(LinkUserProduct $linkUserProduct, array $params = []): void
    {
        if (!$linkUserProduct->save($params)) {
            throw new \RuntimeException(__('client.Save error'));
        }
    }

    /**
     * @param int $user_id
     * @return array|LinkUserProduct[]
     */
    public function findAllByUserId(int $user_id): array
    {
        return LinkUserProduct::query()
            ->where('user_id', $user_id)
            ->get()
            ->all();
    }

    /**
     * @param int $user_id
     * @param int $product_id
     * @return bool
     */
    public function exists(int $user_id, int $product_id): bool
    {
        return LinkUserProduct::query()
            ->where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->exists();
    }
}
