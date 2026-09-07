<?php

namespace App\Core\Services\Auth;

use App\Core\Services\Auth\Interfaces\AuthInterface;
use Illuminate\Foundation\Http\FormRequest;

class SmsAuthService implements AuthInterface
{

    public function login(FormRequest $formRequest)
    {
        // TODO: Implement login() method.
    }
}
