<?php

namespace App\Http\Resources\Links;

use App\Http\Resources\Products\ProductViewResource;
use App\Models\Links\LinkUserProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin LinkUserProduct
 */
class LinkUserProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int, ProductViewResource>
     */
    public function toArray(Request $request): array
    {
        return [
            new ProductViewResource($this->product)
        ];
    }
}
