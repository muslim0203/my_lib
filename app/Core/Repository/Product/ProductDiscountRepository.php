<?php

namespace App\Core\Repository\Product;

use App\Models\Products\ProductDiscount;

class ProductDiscountRepository
{
    /**
     * @param ProductDiscount $productDiscount
     * @param array $options
     * @return void
     */
    public function save(ProductDiscount &$productDiscount, array $options = []): void
    {
        if (!$productDiscount->save($options)) {
            throw new \RuntimeException(__('client.Save Error product discount'));
        }
    }
}
