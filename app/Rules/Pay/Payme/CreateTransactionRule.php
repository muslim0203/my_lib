<?php

namespace App\Rules\Pay\Payme;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class CreateTransactionRule implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $params = $this->data['params'];

        if (empty($params)) {
            $fail('client.Params is require field');
        }

        if (empty($params['amount'])) {
            $fail('client.Amount is require field');
        }

        $account = $params['account'];

        if (empty($account)) {
            $fail('client.Account is require field');
        }

        if (empty($account['order_id'])) {
            $fail('client.OrderId is require field');
        }

        if (empty($params['id'])) {
            $fail('client.Transaction id is require field');
        }

        if (empty($params['time'])) {
            $fail('client.Transaction time is require field');
        }
    }

    /**
     * @param array $data
     * @return $this|CreateTransactionRule
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }
}
