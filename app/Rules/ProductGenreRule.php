<?php

namespace App\Rules;

use App\Core\Repository\Enum\ProductGenresRepository;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;
use Closure;

class ProductGenreRule implements ValidationRule
{
    public function __construct(
        protected bool $isRequire = true
    )
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productGenreRepository = new ProductGenresRepository();

        if ($this->isRequire && empty($value)) {
            $fail(__('validation.Product Genre is require'));
        }

        foreach ($value as $genre) {
            if (!$productGenreRepository->exists($genre)) {
                $fail(__('validation.Product Genre does not exist'));
            }
        }
    }
}
