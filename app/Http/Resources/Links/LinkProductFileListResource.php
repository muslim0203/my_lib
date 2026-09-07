<?php

namespace App\Http\Resources\Links;

use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Links\LinkProductFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin LinkProductFiles
 */
class LinkProductFileListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array|FileViewResource
    {
        return new FileViewResource($this->file);
    }
}
