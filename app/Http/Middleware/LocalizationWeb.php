<?php

namespace App\Http\Middleware;

use App\Core\Enums\LanguageEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LocalizationWeb
{
    /**
     * Sessiyadagi `locale` qiymati ilgari HECH QANDAY tekshiruvsiz
     * `App::setLocale()` ga uzatilardi. `language/{locale}` marshruti esa
     * URL segmentini xuddi shunday tekshiruvsiz sessiyaga yozardi.
     *
     * Natijada bu qiymat `App\Core\Helpers\Lang\LanguageHelper` orqali
     * SQL USTUN IDENTIFIKATORI sifatida so'rovlarga qo'shilib, ikkinchi
     * darajali SQL injection'ga olib kelardi. Identifikatorni parametr
     * sifatida bog'lab bo'lmaydi, shuning uchun yagona to'g'ri yechim -
     * qat'iy oq ro'yxat (allow-list).
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('locale')) {
            $locale = self::sanitize(Session::get('locale'));

            if ($locale === null) {
                // Buzilgan yoki soxta qiymat - sessiyadan olib tashlanadi.
                Session::forget('locale');
            } else {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }

    /**
     * Ruxsat etilgan tillar ro'yxati (App\Core\Enums\LanguageEnum).
     *
     * @return string[]
     */
    public static function allowed(): array
    {
        return array_map(
            static fn(LanguageEnum $case): string => $case->value,
            LanguageEnum::cases()
        );
    }

    /**
     * Faqat oq ro'yxatdagi til kodini qaytaradi, aks holda `null`.
     *
     * @param mixed $locale
     * @return string|null
     */
    public static function sanitize(mixed $locale): ?string
    {
        if (!is_string($locale)) {
            return null;
        }

        return LanguageEnum::tryFrom($locale)?->value;
    }

    /**
     * Standart (fallback) til kodi.
     *
     * @return string
     */
    public static function defaultLocale(): string
    {
        return self::sanitize(config('app.locale')) ?? LanguageEnum::_OZ->value;
    }
}
