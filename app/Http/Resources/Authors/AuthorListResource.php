<?php

namespace App\Http\Resources\Authors;

use App\Http\Resources\Merchant\ProfileFileViewResource;
use App\Models\Authors\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Author
 */
class AuthorListResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'middle_name' => $this->middle_name,
            'profile_file' => new ProfileFileViewResource($this->profileFile)
        ];
    }
}
