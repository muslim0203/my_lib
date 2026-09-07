<?php

namespace App\Http\Resources\Enums;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Enums\EnumCategories;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin EnumCategories
 */
class EnumCategoriesResource extends JsonResource
{
    public $resource = EnumCategories::class;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getId(),
            'text' => $this->{LanguageHelper::getName()},
            'icon' => $this->icon,
            'mega' => !empty($this->parent),
            'parent_id' => $this->getParentId(),
            'megaContent' => !empty($this->parent) ? new self($this->parent) : null,
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(EnumCategories::class);
    }


}
