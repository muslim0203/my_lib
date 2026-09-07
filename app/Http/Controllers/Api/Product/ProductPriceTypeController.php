<?php

namespace App\Http\Controllers\Api\Product;

use App\Core\Repository\Product\ProductPriceTypeRepository;
use App\Http\Resources\Products\PriceTypeListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ProductPriceTypeController extends Controller
{
    /**
     * @param ProductPriceTypeRepository $productPriceTypeRepository
     * @return AnonymousResourceCollection
     */
    public function list(ProductPriceTypeRepository $productPriceTypeRepository): AnonymousResourceCollection
    {
        return PriceTypeListResource::collection($productPriceTypeRepository->findAll());
    }
}
