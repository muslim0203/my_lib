<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Repository\Product\ProductTagRepository;
use App\Http\Resources\Productions\ProductTagListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ProductTagController extends Controller
{
    /**
     * @param ProductTagRepository $productTagRepository
     * @return AnonymousResourceCollection
     */
    public function list(ProductTagRepository $productTagRepository): AnonymousResourceCollection
    {
        return ProductTagListResource::collection($productTagRepository->findAll());
    }
}
