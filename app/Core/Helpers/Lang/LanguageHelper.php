<?php

namespace App\Core\Helpers\Lang;

class LanguageHelper
{
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'name_' . app()->currentLocale();
    }

    /**
     * @return string
     */
    public static function getTitle(): string
    {
        return 'title_' . app()->currentLocale();
    }

    /**
     * @return string
     */
    public static function getMessage(): string
    {
        return 'message_' . app()->currentLocale();
    }

    /**
     * @return string
     */
    public static function getDescription(): string
    {
        return 'description_' . app()->currentLocale();
    }
    public static function getContent(): string
    {
        return 'content_' . app()->currentLocale();
    }
    public static function getAuthor(): string
    {
        return 'author_' . app()->currentLocale();
    }
}
