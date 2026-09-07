<?php

namespace App\Core\Enums\Users;

enum UserStatusEnum: int
{
    case _ACTIVE = 1;
    case _DELETED = 0;
    case _UN_CONFIRMED = 2;


}
