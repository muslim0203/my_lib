<?php

namespace App\Http\Controllers\Api\Company;

use App\Core\Repository\Company\CompanyRepository;
use App\Http\Resources\Company\CompanyResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class CompanyController extends Controller
{
    public function view(CompanyRepository $companyRepository): AnonymousResourceCollection
    {
        return CompanyResource::collection($companyRepository->findOne());
    }
}
