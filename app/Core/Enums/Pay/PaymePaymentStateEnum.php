<?php

namespace App\Core\Enums\Pay;

enum PaymePaymentStateEnum: string
{
    case CHECK_PERFORM_TRANSACTION = '0';
    case CREATE_TRANSACTION = '1';
    case PERFORM_TRANSACTION = '2';
    case CANCEL_TRANSACTION = '-1';
    case CLOSED_TRANSACTION = '-2';
}
