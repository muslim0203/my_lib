<?php

namespace App\Core\Enums\Products;

enum ProductStateEnum: string
{
    case _CHECKING = 'checking';
    case _PROCESSED = 'processed';
    case _REJECTED = 'rejected';

    public static function getList(): array
    {
        return array_column(self::cases(), 'value');
    }

}
