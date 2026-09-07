<?php

namespace App\Http\Resources\Productions;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Enums\EnumProductType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EnumProductType
 */
class ProductTypeListResource extends JsonResource
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
            'name' => $this->{LanguageHelper::getName()}
        ];
    }
}
