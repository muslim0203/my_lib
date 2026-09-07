<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\DB;

class Transaction
{
    public function wrap(callable $function)
    {
        return DB::transaction($function);
    }
}
