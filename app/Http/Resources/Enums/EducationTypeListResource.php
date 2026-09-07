<?php

namespace App\Http\Resources\Enums;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Enums\EnumEducationType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EnumEducationType
 */
class EducationTypeListResource extends JsonResource
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
