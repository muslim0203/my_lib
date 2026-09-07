<?php

namespace App\Core\Enums\Pay;

enum ClickActionCodeEnum: int
{
    case CODE_PREPARE = 0;
    case CODE_COMPLETE = 1;
}
