<?php

namespace App\Core\Enums\Auth;

enum GuardsEnum: string
{
    case _MAIL = 'mail';
    case _GOOGLE = 'google';
    case _PHONE = 'phone';
    case _WEB = 'web';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
