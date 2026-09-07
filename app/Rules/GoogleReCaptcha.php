<?php

namespace App\Rules;

use App\Core\Helpers\Requests\ValidationException;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class GoogleReCaptcha implements ValidationRule
{
    use ValidationException;

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::get(config('captcha.google-captcha.url'), [
            'secret'   => config('captcha.google-captcha.secret-key'),
            'response' => $value
        ]);

        if (!($response->object()->success && $response->object()->score >= 0.5 && $response->object()->action == 'register_data')) {
            $fail('Google recaptcha unconfirmed.');
        }
    }
}
