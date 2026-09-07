<?php

namespace App\Core\Services\Links;

use App\Core\Repository\LInks\LinkProductTypeRepository;
use App\Models\Links\LinkProductType;
use Illuminate\Foundation\Http\FormRequest;

class LinkProductTypeService
{
    public function __construct(
        protected LinkProductTypeRepository $linkProductTypeRepository
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
                $model = new LinkProductType();
                $model->setTypeId($item);
                $model->setProductId($product_id);
                $this->linkProductTypeRepository->save($model);
            }
        }

        if ($formRequest instanceof FormRequest) {
            $model = new LinkProductType();
            $model->fill($formRequest->validated());
            $model->setProductId($product_id);
            $this->linkProductTypeRepository->save($model);
        }
    }
}
