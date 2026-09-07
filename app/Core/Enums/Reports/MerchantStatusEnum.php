<?php

namespace App\Core\Enums\Reports;

enum MerchantStatusEnum: string
{
    case _NEW = 'new';
    case _PROCESS = 'process';
    case _PROCESSED = 'processed';
}
