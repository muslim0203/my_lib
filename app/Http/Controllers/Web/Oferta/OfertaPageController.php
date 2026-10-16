<?php

namespace App\Http\Controllers\Web\Oferta;

use App\Core\Enums\LanguageEnum;
use App\Core\Services\Oferta\OfertaService;
use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;

class OfertaPageController extends Controller
{
    /**
     * Ommaviy (login talab qilinmaydi) oferta sahifasi: /oferta/{oz|uz|ru}.
     */
    public function show(OfertaService $ofertaService, ?string $locale = null): View
    {
        if ($locale !== null && LanguageEnum::tryFrom($locale) === null) {
            abort(404);
        }

        $locale = $ofertaService->locale($locale);

        return view('pages.oferta.show', [
            'locale' => $locale,
            'title' => $ofertaService->title($locale),
            'content' => $ofertaService->content($locale),
            'languages' => [
                LanguageEnum::_OZ->value => 'O‘zbekcha',
                LanguageEnum::_UZ->value => 'Ўзбекча',
                LanguageEnum::_RU->value => 'Русский',
            ],
        ]);
    }
}
