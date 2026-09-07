<?php

namespace App\Core\Services\Auth\Interfaces;

use Illuminate\Foundation\Http\FormRequest;

interface AuthInterface
{
    public function login(FormRequest $formRequest);
}
