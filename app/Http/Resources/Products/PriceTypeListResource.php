<?php

namespace App\Http\Resources\Products;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Products\ProductPriceType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductPriceType
 */
class PriceTypeListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->{LanguageHelper::getName()},
            'content' => $this->{LanguageHelper::getContent()},
            'percentage' => $this->getPercentage() . '%',
        ];
    }
}
