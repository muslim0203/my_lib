<?php

namespace App\Http\Controllers\Api\Company;

use App\Core\Repository\Company\CompanyPartnerRepository;
use App\Http\Resources\Company\CompanyPartnerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class CompanyPartnerController extends Controller
{
    public function view(CompanyPartnerRepository $companyPartnerRepository): AnonymousResourceCollection
    {
        return CompanyPartnerResource::collection($companyPartnerRepository->findAll());
    }
}
