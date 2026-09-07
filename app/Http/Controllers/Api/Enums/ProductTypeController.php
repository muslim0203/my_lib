<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Repository\Product\ProductTypeRepository;
use App\Http\Resources\Productions\ProductTypeListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ProductTypeController extends Controller
{
    /**
     * @param ProductTypeRepository $productTypeRepository
     * @return AnonymousResourceCollection
     */
    public function list(ProductTypeRepository $productTypeRepository): AnonymousResourceCollection
    {
        return ProductTypeListResource::collection($productTypeRepository->findAll());
    }
}
