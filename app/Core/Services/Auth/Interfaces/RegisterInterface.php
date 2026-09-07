<?php

namespace App\Core\Services\Auth\Interfaces;


use App\Http\Requests\Auth\RegisterByEmailRequest;
use App\Http\Requests\Auth\VerifyMailTokenRequest;
use Illuminate\Foundation\Http\FormRequest;

interface RegisterInterface
{
    public function register(RegisterByEmailRequest $registerByEmailRequest): int;

    public function sendTokenToMail(FormRequest $formRequest): bool;

    public function verifyMail(VerifyMailTokenRequest $verifyMailTokenRequest): int;
}
