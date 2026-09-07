<?php

namespace App\Core\Helpers\Lang;

class CurrentLanguage
{
    public static function get()
    {
        $lang = app()->currentLocale();

    }
}
