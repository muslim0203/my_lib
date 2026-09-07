<?php

namespace App\Rules;

use App\Core\Repository\Enum\ProductTagRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProductTagRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productTagRepository = new ProductTagRepository();

        if (empty($value)) {
            $fail(__('validation.Product Tag is require'));
        }

        foreach ($value as $genre) {
            if (!$productTagRepository->exists($genre)) {
                $fail(__('validation.Product Tag does not exist'));
            }
        }
    }
}
