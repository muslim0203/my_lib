<?php

namespace App\Rules\Pay\Payme;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckTransactionRule implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $params = $this->data['params'];

        if (empty($params)) {
            $fail('client.Params is require field');
        }

        if (empty($params['id'])) {
            $fail('client.Transaction id is require field');
        }
    }

    /**
     * @param array $data
     * @return $this|CheckTransactionRule
     */
    public function setData(array $data): CheckTransactionRule|static
    {
        $this->data = $data;
        return $this;
    }
}
