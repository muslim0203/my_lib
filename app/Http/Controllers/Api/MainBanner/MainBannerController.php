<?php

namespace App\Http\Controllers\Api\MainBanner;

use App\Core\Repository\MainBanner\MainBannerRepository;
use App\Http\Resources\MainBanner\MainBannerResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;

class MainBannerController extends Controller
{
    public function list(MainBannerRepository $mainBannerRepository): AnonymousResourceCollection
    {
        return MainBannerResource::collection($mainBannerRepository->findOne());
    }
}
