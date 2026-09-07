<?php

namespace App\Core\Filters\Product;

use App\Models\Products\ProductsOrder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductsOrderFilter
{
    /**
     * @param int $user_id
     * @return LengthAwarePaginator
     */
    public static function search(int $user_id): LengthAwarePaginator
    {
        $query = ProductsOrder::query()->where('customer_id', $user_id)->where('enabled', true);

        return $query->paginate(30);
    }
}
