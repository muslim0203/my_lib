<?php

namespace App\Core\Services\Links;

use App\Core\Repository\LInks\LinkProductCategoryRepository;
use App\Models\Links\LinkProductCategories;
use Illuminate\Foundation\Http\FormRequest;

class LinkProductCategoryService
{
    public function __construct(
        protected LinkProductCategoryRepository $linkProductCategoryRepository
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
            foreach ($formRequest as $value) {
                $model = new LinkProductCategories();
                $model->setCategoryId($value);
                $model->setProductId($product_id);
                $this->linkProductCategoryRepository->save($model);
            }
        }

        if ($formRequest instanceof FormRequest) {
            $model = new LinkProductCategories();
            $model->fill($formRequest->all());
            $model->setProductId($product_id);
            $this->linkProductCategoryRepository->save($model);
        }
    }
}
