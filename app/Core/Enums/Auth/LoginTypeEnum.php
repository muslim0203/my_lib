<?php

namespace App\Core\Enums\Auth;

enum LoginTypeEnum: string
{
    case _LOGIN_EMAIL = 'email';
    case _LOGIN_GOOGLE = 'google';
    case _LOGIN_SMS = 'sms';
    case _LOGIN_LOGIN_PASS = 'login-password';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
