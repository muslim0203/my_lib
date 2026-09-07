<?php

namespace App\Core\Repository\Product;

use App\Models\Products\ProductComment;

class ProductCommentRepository
{
    /**
     * @param ProductComment $productComment
     * @param array $options
     * @return void
     */
    public function save(ProductComment $productComment, array $options = []): void
    {
        if (!$productComment->save($options)) {
            throw new \RuntimeException(__('client.Save error'));
        }
    }
}
