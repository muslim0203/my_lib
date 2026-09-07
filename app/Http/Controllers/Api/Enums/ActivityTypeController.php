<?php

namespace App\Http\Controllers\Api\Enums;

use App\Core\Helpers\Response\Success;
use App\Core\Repository\Enum\ActivityTypeRepository;
use App\Http\Resources\Enums\ActivityTypeListResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ActivityTypeController extends Controller
{
    public function __construct(
        protected ActivityTypeRepository $activityTypeRepository
    )
    {

    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        return Success::send('Activity type list', ActivityTypeListResource::collection($this->activityTypeRepository->findAll()));
    }
}
