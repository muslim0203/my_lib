<?php

namespace App\Http\Controllers\Api\Company;

use App\Core\Repository\Company\CompanySocialNetworkRepository;
use App\Http\Resources\Company\CompanySocialNetworkResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class CompanySocialNetworkController extends Controller
{
    public function view(CompanySocialNetworkRepository $companySocialRepository): AnonymousResourceCollection
    {
        return CompanySocialNetworkResource::collection($companySocialRepository->findAll());
    }
}
