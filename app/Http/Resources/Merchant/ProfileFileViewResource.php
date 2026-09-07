<?php

namespace App\Http\Resources\Merchant;

use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Authority\AuthorityFile;
use App\Models\Authors\AuthorProfileFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AuthorityFile|AuthorProfileFile
 */
class ProfileFileViewResource extends JsonResource
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
            'file' => new FileViewResource($this->file)
        ];
    }
}
