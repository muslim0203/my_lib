<?php

namespace App\Core\Services\Oferta;

use App\Core\Enums\LanguageEnum;
use App\Core\Helpers\Lang\LanguageHelper;

/**
 * Vositachilik shartnomasi (oferta) matnlari.
 *
 * Matnlar `resources/oferta/{til}.html` da saqlanadi (16.10.2026 dagi
 * tahrir; 28.09.2024 dagi .docx asl nusxalari asosida). Ular bizning o'zimizning fayllarimiz,
 * foydalanuvchi kiritgan ma'lumot emas, shuning uchun sahifada xom HTML
 * sifatida chiqariladi. Fayl nomi faqat LanguageEnum oq ro'yxatidan
 * olinadi.
 */
class OfertaService
{
    /** Oferta tahririning sanasi. Matn o'zgarganda yangilanadi. */
    public const VERSION = '2026-10-16';

    public function locale(?string $locale = null): string
    {
        return LanguageEnum::tryFrom((string) $locale)?->value ?? LanguageHelper::locale();
    }

    public function content(string $locale): string
    {
        return (string) file_get_contents(resource_path('oferta/' . $this->locale($locale) . '.html'));
    }

    public function title(string $locale): string
    {
        preg_match('~<h1>(.*?)</h1>~u', $this->content($locale), $matches);

        return html_entity_decode($matches[1] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
