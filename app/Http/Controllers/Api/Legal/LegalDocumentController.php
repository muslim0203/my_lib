<?php

namespace App\Http\Controllers\Api\Legal;

use App\Core\Repository\Company\CompanyRepository;
use App\Core\Services\Legal\LegalDocumentService;
use App\Http\Controllers\Web\Legal\LegalDocumentPageController;
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

        $data = $service->toArray($document, $service->locale());
        $data['contacts'] = LegalDocumentPageController::contacts(app(CompanyRepository::class));

        return response()->json(['data' => $data]);
    }

    /**
     * Eski manzil: GET /api/oferta - sotuvchilar ofertasi.
     */
    public function sellerOffer(LegalDocumentService $service): JsonResponse
    {
        return $this->view($service, LegalDocumentService::SELLER_OFFER);
    }
}
