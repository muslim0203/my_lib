<?php

namespace App\Rules;

use App\Core\Repository\Enum\ProductTypeRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ProductTypeRule implements ValidationRule
{
    public function __construct(protected bool $isRequire = true)
    {

    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $productTagRepository = new ProductTypeRepository();

        if ($this->isRequire && empty($value)) {
            $fail(__('validation.Product Type is require'));
        }

        foreach ($value as $genre) {
            if (!$productTagRepository->exists($genre)) {
                $fail(__('validation.Product Type does not exist'));
            }
        }
    }
}
