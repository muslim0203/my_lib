<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PassportRule implements ValidationRule
{
    protected string $pattern = '/^([A-Z]{2})([0-9]{7})$/';

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (preg_match($this->pattern, $value) !== 1) {
            $fail(__('client.Passport number is not match'));
        }
    }
}
