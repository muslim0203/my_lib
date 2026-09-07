<?php

namespace App\Core\Services\Product;

use App\Core\Repository\Product\ProductDiscountRepository;
use App\Models\Products\ProductDiscount;
use Illuminate\Foundation\Http\FormRequest;

class ProductDiscountService
{
    public function __construct(
        protected ProductDiscountRepository $productDiscountRepository
    )
    {
    }

    /**
     * @param int $product_id
     * @param FormRequest $formRequest
     * @return ProductDiscount
     */
    public function create(int $product_id, FormRequest $formRequest): ProductDiscount
    {
        $model = new ProductDiscount();

        if (empty($formRequest->post('from_expire_at'))) {
            $model->setFromExpireAt(date('Y-m-d'));
        }

        $model->fill($formRequest->all());
        $model->setProductId($product_id);
        $this->productDiscountRepository->save($model);
        return $model;
    }
}
