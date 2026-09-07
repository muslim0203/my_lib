<?php

namespace App\Core\Enums\Users;

enum PermissionEnum: string
{
    case _USER = 'user';
    case _MERCHANT = 'merchant';
    case _PHONE = 'phone';
    case _ADMIN = 'admin';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
