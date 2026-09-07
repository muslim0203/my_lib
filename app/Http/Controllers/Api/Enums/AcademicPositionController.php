<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Repository\Enum\AcademicPositionRepository;
use App\Http\Resources\Enums\AcademicPositionListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class AcademicPositionController extends Controller
{
    /**
     * @param AcademicPositionRepository $academicPositionRepository
     * @return AnonymousResourceCollection
     */
    public function list(AcademicPositionRepository $academicPositionRepository): AnonymousResourceCollection
    {
        return AcademicPositionListResource::collection($academicPositionRepository->findAll());
    }
}
