<?php

namespace App\Rules;

use App\Models\Files\File;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class FilesRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!empty($value)) {

            if (count($value) > 10) {
                $fail(__('validation.File max count 10.'));
            }

            foreach ($value as $item) {
                if (!File::query()->where('id', $item['file_id'])->exists()) {
                    $fail(__('validation.File is not found'));
                }

                if (empty($item['file_name'])) {
                    $fail(__('validation.File name is required'));
                }
            }
        }
    }
}
