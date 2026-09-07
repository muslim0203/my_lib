<?php

namespace App\Rules;

use App\Models\Files\File;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class FileExistsRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value)) {

            if (count($value) > 100) {
                $fail(__('validation.File max count 100.'));
            }

            foreach ($value as $item) {
                if (!File::query()->where('id', $item)->exists()) {
                    $fail(__('validation.File is not found'));
                }
            }
        }
    }
}
