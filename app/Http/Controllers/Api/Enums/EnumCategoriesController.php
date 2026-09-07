<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\CategoriesRepository;
use App\Http\Resources\Enums\EnumCategoriesResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class EnumCategoriesController extends Controller
{
    private CategoriesRepository $enumCategoriesRepository;

    public function __construct(
        CategoriesRepository $enumCategoriesRepository
    )
    {
        $this->enumCategoriesRepository = $enumCategoriesRepository;
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => __('client.Success'),
            'categories'    => $this->enumCategoriesRepository->findList()
        ]);
    }
}
