<?php

namespace App\Rules\Pay\Payme;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CancelTransactionRule implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
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

        if (empty($params['reason'])) {
            $fail('client.Reason is require field');
        }
    }

    /**
     * @param array $data
     * @return $this|CancelTransactionRule
     */
    public function setData(array $data): CancelTransactionRule|static
    {
        $this->data = $data;

        return $this;
    }
}
