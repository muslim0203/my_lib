<?php

namespace App\Core\Services\Legal;

use App\Core\Enums\LanguageEnum;
use App\Core\Helpers\Lang\LanguageHelper;

/**
 * Saytning huquqiy hujjatlari: sotuvchilar ofertasi, xaridorlar ofertasi
 * va maxfiylik siyosati.
 *
 * Matnlar `resources/legal/{hujjat}/{til}.html` da saqlanadi. Ular
 * repozitoriydagi o'zimizning fayllarimiz, foydalanuvchi kiritgan ma'lumot
 * emas, shuning uchun sahifada xom HTML sifatida chiqariladi. Fayl yo'li
 * faqat DOCUMENTS va LanguageEnum oq ro'yxatlaridan yasaladi.
 */
class LegalDocumentService
{
    public const SELLER_OFFER = 'seller-offer';
    public const BUYER_OFFER = 'buyer-offer';
    public const PRIVACY_POLICY = 'privacy-policy';

    /**
     * Hujjat => tahrir sanasi. Matn o'zgarganda sanani yangilang.
     */
    public const DOCUMENTS = [
        self::SELLER_OFFER => '2026-10-16',
        self::BUYER_OFFER => '2026-10-16',
        self::PRIVACY_POLICY => '2026-10-16',
    ];

    public function exists(string $document): bool
    {
        return array_key_exists($document, self::DOCUMENTS);
    }

    public function locale(?string $locale = null): string
    {
        return LanguageEnum::tryFrom((string) $locale)?->value ?? LanguageHelper::locale();
    }

    public function version(string $document): string
    {
        return self::DOCUMENTS[$document];
    }

    public function content(string $document, string $locale): string
    {
        if (!$this->exists($document)) {
            throw new \InvalidArgumentException("Unknown legal document [$document].");
        }

        return (string) file_get_contents(resource_path('legal/' . $document . '/' . $this->locale($locale) . '.html'));
    }

    public function title(string $document, string $locale): string
    {
        preg_match('~<h1>(.*?)</h1>~u', $this->content($document, $locale), $matches);

        return html_entity_decode($matches[1] ?? '', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    /**
     * API va sahifa uchun umumiy ko'rinish.
     */
    public function toArray(string $document, string $locale): array
    {
        $locale = $this->locale($locale);

        return [
            'document' => $document,
            'lang' => $locale,
            'version' => $this->version($document),
            'title' => $this->title($document, $locale),
            'content' => $this->content($document, $locale),
            'url' => route('legal.show', ['document' => $document, 'locale' => $locale]),
        ];
    }
}
