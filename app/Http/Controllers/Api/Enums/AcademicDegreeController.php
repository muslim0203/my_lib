<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Repository\Enum\AcademicDegreeRepository;
use App\Http\Resources\Enums\AcademicDegreeListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class AcademicDegreeController extends Controller
{
    /**
     * @param AcademicDegreeRepository $academicDegreeRepository
     * @return AnonymousResourceCollection
     */
    public function list(AcademicDegreeRepository $academicDegreeRepository): AnonymousResourceCollection
    {
        return AcademicDegreeListResource::collection($academicDegreeRepository->findAll());
    }
}
