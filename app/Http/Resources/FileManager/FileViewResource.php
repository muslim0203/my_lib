<?php

namespace App\Http\Resources\FileManager;

use App\Models\Files\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin File
 */
class FileViewResource extends JsonResource
{
    public function __construct(
        $resource,
        protected bool $isOldFile = false
    )
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->getId(),
            'file'          => $this->getSrc(),
            'original_name' => $this->getOriginalName(),
            'file_name'     => $this->getFileName(),
            'is_old_file'   => $this->isOldFile
        ];
    }
}
