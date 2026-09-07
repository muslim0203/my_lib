<?php

namespace App\Core\Services\Register\Contracts;

use Illuminate\Foundation\Http\FormRequest;

interface Register
{
    public function register(FormRequest $formRequest, ?int $id = null): int;
}
