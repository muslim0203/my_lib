<?php

namespace App\Core\Services\Auth;

use App\Core\Services\Auth\Interfaces\AuthInterface;
use Illuminate\Foundation\Http\FormRequest;

class AuthGateway
{
    public function __construct(
        protected AuthInterface $auth
    )
    {
    }

    public function login(FormRequest $formRequest)
    {
        return $this->auth->login($formRequest);
    }
}
