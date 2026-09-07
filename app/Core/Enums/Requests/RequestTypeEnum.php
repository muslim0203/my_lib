<?php

namespace App\Core\Enums\Requests;

enum RequestTypeEnum: int
{
    case _PRODUCT = 1;
    case _AUTHOR = 2;
    case _AUTHORITY = 3;

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
