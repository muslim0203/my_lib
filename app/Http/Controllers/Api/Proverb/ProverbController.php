<?php

namespace App\Http\Controllers\Api\Proverb;

use App\Core\Repository\Proverb\ProverbRepository;
use App\Http\Resources\ProverbResource\ProverbResource;
use Illuminate\Routing\Controller;

class ProverbController extends Controller
{
    public function list(ProverbRepository $proverbRepository)
    {
        return $proverbRepository->findRandom();
    }
}
