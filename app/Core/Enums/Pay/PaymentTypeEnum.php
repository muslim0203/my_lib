<?php

namespace App\Core\Enums\Pay;

enum PaymentTypeEnum: string
{
    case TYPE_PAYME = 'payme';
    case TYPE_CLICK = 'click';
    case TYPE_FREE = 'free';
}
