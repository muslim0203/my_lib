<?php

namespace App\Core\Helpers\Lang;

use App\Core\Enums\LanguageEnum;

class LanguageHelper
{
    /**
     * Returns the current locale, but ONLY when it is one of the locales
     * declared in App\Core\Enums\LanguageEnum.
     *
     * The return value of this class is interpolated into raw SQL as a COLUMN
     * IDENTIFIER (see App\Core\Filters\Reports\*). Identifiers cannot be bound
     * as parameters, therefore the only safe strategy is a strict allow-list.
     * Anything that is not an enum case falls back to the configured default
     * locale, and finally to LanguageEnum::_OZ.
     *
     * @return string
     */
    public static function locale(): string
    {
        $locale = (string) app()->getLocale();

        if (LanguageEnum::tryFrom($locale) !== null) {
            return $locale;
        }

        $fallback = (string) config('app.locale', LanguageEnum::_OZ->value);

        if (LanguageEnum::tryFrom($fallback) !== null) {
            return $fallback;
        }

        return LanguageEnum::_OZ->value;
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'name_' . self::locale();
    }

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return 'title_' . self::locale();
    }

    /**
     * @return string
     */
    public static function getMessage(): string
    {
        return 'message_' . self::locale();
    }

    /**
     * @return string
     */
    public static function getDescription(): string
    {
        return 'description_' . self::locale();
    }

    /**
     * @return string
     */
    public static function getContent(): string
    {
        return 'content_' . self::locale();
    }

    /**
     * @return string
     */
    public static function getAuthor(): string
    {
        return 'author_' . self::locale();
    }
}
