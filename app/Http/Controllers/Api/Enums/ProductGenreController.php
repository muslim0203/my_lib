<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Repository\Product\ProductGenreRepository;
use App\Http\Resources\Productions\ProductGenreListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class ProductGenreController extends Controller
{
    /**
     * @param ProductGenreRepository $productGenreRepository
     * @return AnonymousResourceCollection
     */
    public function list(ProductGenreRepository $productGenreRepository): AnonymousResourceCollection
    {
        return ProductGenreListResource::collection($productGenreRepository->findAllBySelect());
    }
}
