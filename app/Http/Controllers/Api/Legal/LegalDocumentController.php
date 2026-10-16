<?php

namespace App\Http\Controllers\Api\Legal;

use App\Core\Services\Legal\LegalDocumentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class LegalDocumentController extends Controller
{
    /**
     * Barcha hujjatlar ro'yxati (matnsiz), joriy til bo'yicha.
     */
    public function list(LegalDocumentService $service): JsonResponse
    {
        $locale = $service->locale();

        $data = array_map(static function (string $document) use ($service, $locale): array {
            $item = $service->toArray($document, $locale);
            unset($item['content']);

            return $item;
        }, array_keys(LegalDocumentService::DOCUMENTS));

        return response()->json(['data' => $data]);
    }

    /**
     * Bitta hujjat matni (Accept-Language: oz | uz | ru).
     */
    public function view(LegalDocumentService $service, string $document): JsonResponse
    {
        abort_unless($service->exists($document), 404);

        return response()->json(['data' => $service->toArray($document, $service->locale())]);
    }

    /**
     * Eski manzil: GET /api/oferta - sotuvchilar ofertasi.
     */
    public function sellerOffer(LegalDocumentService $service): JsonResponse
    {
        return $this->view($service, LegalDocumentService::SELLER_OFFER);
    }
}
