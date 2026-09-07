<?php

namespace App\Core\Enums\Genders;

enum GenderEnum: string
{
    case _MALE = 'm';
    case _FEMALE = 'f';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
