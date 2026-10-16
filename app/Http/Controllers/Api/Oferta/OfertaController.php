<?php

namespace App\Http\Controllers\Api\Oferta;

use App\Core\Services\Oferta\OfertaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class OfertaController extends Controller
{
    /**
     * Joriy til (Accept-Language: oz | uz | ru) bo'yicha oferta matni.
     */
    public function view(OfertaService $ofertaService): JsonResponse
    {
        $locale = $ofertaService->locale();

        return response()->json([
            'data' => [
                'lang' => $locale,
                'version' => OfertaService::VERSION,
                'title' => $ofertaService->title($locale),
                'content' => $ofertaService->content($locale),
                'url' => route('oferta.show', ['locale' => $locale]),
            ],
        ]);
    }
}
