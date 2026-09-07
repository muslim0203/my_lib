<?php

namespace App\Core\Services\Auth;

use App\Core\Services\Auth\Interfaces\AuthInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LoginAndPasswordService implements AuthInterface
{
    /**
     * @param FormRequest $formRequest
     * @return array
     */
    public function login(FormRequest $formRequest): array
    {
        $credentials = $formRequest->validated();

        if (Auth::attempt($credentials)) {
            $formRequest->session()->regenerate();

            return [
                'success' => true
            ];
        }

        return [
            'success' => false,
            'errors'  => [
                'username' => 'The provided credentials do not match our records.'
            ]
        ];
    }
}
