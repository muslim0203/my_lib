<?php

namespace App\Http\Resources\Authors;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Http\Resources\Merchant\ProfileFileViewResource;
use App\Models\Authority\Authority;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Authority
 */
class AuthorityListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->{LanguageHelper::getName()},
            'description' => $this->{LanguageHelper::getDescription()},
            'profile_file' => new ProfileFileViewResource($this->profileFile)
        ];
    }
}
