<?php

namespace App\Core\Enums\Reports;

enum ReportTypeEnum: int
{
    case USERS_LOT_BOUGHT = 1;
    case BOOKS_LOT_BOUGHT = 2;
    case BOOKS_LOT_BENEFIT_BOUGHT = 3;
    case BOOKS_LOW_BOUGHT = 4;
    case BOOKS_LOW_BENEFIT_BOUGHT = 5;
    case BOOKS_NOT_BOUGHT = 6;
    case BOOKS_FREE = 7;
}
