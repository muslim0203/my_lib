<?php

namespace App\Http\Resources\Products;

use App\Models\Products\ProductsOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductsOrder
 */
class ProductsOrderListResource extends JsonResource
{
    /**
     * @param Request $request
     * @return ProductViewResource
     */
    public function toArray(Request $request)
    {
        return new ProductViewResource($this->product);
    }
}
