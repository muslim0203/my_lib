<?php

namespace App\Rules;

use App\Core\Repository\Enum\CategoriesRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ProductCategoryRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $categoryRepository = new CategoriesRepository();

        if (empty($value)) {
            $fail(__('validation.Product Category is require'));
        }

        foreach ($value as $genre) {
            if (!$categoryRepository->exists($genre)) {
                $fail(__('validation.Product Category does not exist'));
            }
        }
    }
}
