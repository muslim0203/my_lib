<?php

namespace App\Rules\Auth;

use Closure;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Contracts\Validation\ValidationRule;

class EmailRegExRule implements ValidationRule
{
    const REGULAR_PATTERN = '';

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (str_contains($value, '+')) {
            $fail(__('Not allowed to plus in email'));
        }
    }
}
