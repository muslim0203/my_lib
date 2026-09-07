<?php

namespace App\Core\Enums\Steps;

enum ProcessStepEnum: string
{
    case _PROCESS_ADD_NEW_AUTHOR = 'process_add_new_author';
    case _REVOKED_ADD_NEW_AUTHOR = 'revoked_add_new_author';
    case _CONFIRMED_ADD_NEW_AUTHOR = 'confirmed_add_new_author';
    case _PROCESS_NEW_PRODUCT_REQUEST = 'new_product_request';
    case _REVOKED_PRODUCT_REQUEST = 'rejected_add_new_product_request';
    case _CONFIRMED_PRODUCT_REQUEST = 'confirmed_add_new_product_request';
    case _PROCESS_NEW_AUTHORITY_REQUEST = 'new_authority_request';
    case _REVOKED_AUTHORITY_REQUEST = 'rejected_add_new_authority_request';
    case _CONFIRMED_AUTHORITY_REQUEST = 'confirmed_add_new_authority_request';

    /**
     * @return array
     */
    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }
}
