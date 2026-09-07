<?php

namespace App\Http\Resources\ProverbResource;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Models\Proverbs\Proverb;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Proverb
 */
class ProverbResource extends JsonResource
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
            'author' => $this->{LanguageHelper::getAuthor()},
            'content' => $this->{LanguageHelper::getContent()}
        ];
    }
}
