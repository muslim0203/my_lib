<?php

namespace App\Http\Controllers\Web\Legal;

use App\Core\Enums\LanguageEnum;
use App\Core\Repository\Company\CompanyRepository;
use App\Core\Services\Legal\LegalDocumentService;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Throwable;

class LegalDocumentPageController extends Controller
{
    /**
     * Ommaviy (login talab qilinmaydi) sahifa: /legal/{hujjat}/{oz|uz|ru}.
     */
    public function show(
        LegalDocumentService $service,
        CompanyRepository $companyRepository,
        string $document,
        ?string $locale = null
    ): View {
        abort_unless($service->exists($document), 404);

        if ($locale !== null && LanguageEnum::tryFrom($locale) === null) {
            abort(404);
        }

        $locale = $service->locale($locale);

        return view('pages.legal.show', [
            'document' => $document,
            'locale' => $locale,
            'title' => $service->title($document, $locale),
            'content' => $service->content($document, $locale),
            'contacts' => $this->contacts($companyRepository),
            'languages' => [
                LanguageEnum::_OZ->value => 'O‘zbekcha',
                LanguageEnum::_UZ->value => 'Ўзбекча',
                LanguageEnum::_RU->value => 'Русский',
            ],
            'documents' => array_keys(LegalDocumentService::DOCUMENTS),
            'documentTitles' => array_combine(
                array_keys(LegalDocumentService::DOCUMENTS),
                array_map(
                    static fn(string $doc): string => $service->title($doc, $locale),
                    array_keys(LegalDocumentService::DOCUMENTS)
                )
            ),
        ]);
    }

    /**
     * Sotuvchilar ofertasining eski manzili: /oferta/{locale?}.
     */
    public function sellerOffer(
        LegalDocumentService $service,
        CompanyRepository $companyRepository,
        ?string $locale = null
    ): View {
        return $this->show($service, $companyRepository, LegalDocumentService::SELLER_OFFER, $locale);
    }

    /**
     * Admin panelda "Kompaniya" bo'limida kiritilgan email va telefon.
     * Hujjatlarda 1-tarafning aloqa ma'lumotlari shu yerdan olinadi.
     * Baza ishlamasa ham sahifa ochilishi kerak.
     *
     * @return array{email: ?string, phone: ?string}
     */
    private function contacts(CompanyRepository $companyRepository): array
    {
        try {
            $company = $companyRepository->findOne()->first();
        } catch (Throwable) {
            $company = null;
        }

        return [
            'email' => $company?->email ?: null,
            'phone' => $company?->phone ?: null,
        ];
    }
}
