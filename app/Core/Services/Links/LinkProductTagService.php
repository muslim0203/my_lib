<?php

namespace App\Core\Services\Links;

use App\Core\Repository\LInks\LinkProductTagRepository;
use App\Models\Links\LinkProductTag;
use Illuminate\Foundation\Http\FormRequest;

class LinkProductTagService
{
    public function __construct(
        protected LinkProductTagRepository $linkProductTagRepository
    )
    {
    }

    /**
     * @param array|FormRequest $formRequest
     * @param int $product_id
     * @return void
     */
    public function save(array|FormRequest $formRequest, int $product_id): void
    {
        if (is_array($formRequest) && !empty($formRequest)) {
            foreach ($formRequest as $item) {
                $model = new LinkProductTag();
                $model->setTagId($item);
                $model->setProductId($product_id);
                $this->linkProductTagRepository->save($model);
            }
        }

        if ($formRequest instanceof FormRequest) {
            $model = new LinkProductTag();
            $model->fill($formRequest->validated());
            $model->setProductId($product_id);
            $this->linkProductTagRepository->save($model);
        }
    }
}
