<?php

namespace App\Core\Services\Authority\Contract;

interface AuthorityProfileFile
{
    public function sync(int $file_id, int $authority_id, string $file_name): void;
}
