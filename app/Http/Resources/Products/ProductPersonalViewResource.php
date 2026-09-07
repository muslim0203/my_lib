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
class ProductPersonalViewResource extends JsonResource
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
            'title_oz' => $this->getTitleOz(),
            'title_uz' => $this->getTitleUz(),
            'title_ru' => $this->getTitleRu(),
            'description_oz' => $this->getDescriptionOz(),
            'description_uz' => $this->getDescriptionUz(),
            'description_ru' => $this->getDescriptionRu(),
            'status_id' => $this->getStatusId(),
            'status' => $this->status?->{LanguageHelper::getName()},
            'step_id' => $this->getStepId(),
            'step' => $this->step?->{LanguageHelper::getName()},
            'parent_id' => $this->getParentId(),
            'parent' => $this->parent?->{LanguageHelper::getTitle()},
            'size' => $this->getSize(),
            'wrapper_file' => new FileViewResource($this->wrapperFile),
            'source_file' => new FileViewResource($this->sourceFile),
            'state' => $this->getState(),
            'price_id' => $this->getPriceId(),
            'price' => $this->price?->{LanguageHelper::getName()},
            'price_type_id' => $this->getPriceTypeId(),
            'price_type' => $this->priceType?->{LanguageHelper::getName()},
            'confirm_author' => $this->confirmAuthor?->getUsername(),
            'confirm_author_id' => $this->getConfirmAuthorId(),
            'last_update_date' => $this->getLastUpdatedDate(),
            'create_date' => $this->getCreateDate(),
            'extra_authors' => $this->getExtraAuthors(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'price_value' => $this->getPriceValue(),
            'discount_id' => $this->getDiscountId(),
            'discount' => $this->discount?->{LanguageHelper::getName()},
            'is_download' => $this->isIsDownload()
        ];
    }
}
