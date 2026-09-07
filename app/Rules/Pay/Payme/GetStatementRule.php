<?php

namespace App\Rules\Pay\Payme;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class GetStatementRule implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    /**
     * Run the validation rule.
     *
     * @param \Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $params = $this->data['params'];

        if (empty($params)) {
            $fail('client.Params is require field');
        }

        if (empty($params['from']) || empty($params['to'])) {
            $fail(__('client.From and to is require field'));
        }
    }

    /**
     * @param array $data
     * @return $this|GetStatementRule
     */
    public function setData(array $data): GetStatementRule|static
    {
        $this->data = $data;
        return $this;
    }
}
