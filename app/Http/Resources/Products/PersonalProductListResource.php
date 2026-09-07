<?php

namespace App\Http\Resources\Products;

use App\Core\Helpers\Lang\LanguageHelper;
use App\Http\Resources\FileManager\FileViewResource;
use App\Models\Products\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class PersonalProductListResource extends JsonResource
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
            'title' => $this->{LanguageHelper::getTitle()},
            'description' => $this->{LanguageHelper::getDescription()},
            'status' => $this->status->{LanguageHelper::getName()},
            'wrapper_file_id' => new FileViewResource($this->wrapperFile),
            'state' => $this->getState(),
            'parent_id' => $this->getParentId(),
            'parent' => $this->parent?->{LanguageHelper::getTitle()},
            'is_deleted' => $this->isIsDeleted(),
            'is_download' => $this->isIsDownload()
        ];
    }
}
