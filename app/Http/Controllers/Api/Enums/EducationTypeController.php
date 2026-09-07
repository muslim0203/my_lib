<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Repository\Enum\EducationTypeRepository;
use App\Http\Resources\Enums\EducationTypeListResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class EducationTypeController extends Controller
{
    /**
     * @param EducationTypeRepository $educationTypeRepository
     * @return AnonymousResourceCollection
     */
    public function list(EducationTypeRepository $educationTypeRepository): AnonymousResourceCollection
    {
        return EducationTypeListResource::collection($educationTypeRepository->findAll());
    }
}
