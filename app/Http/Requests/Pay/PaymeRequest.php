<?php

namespace App\Http\Requests\Pay;

use App\Core\Enums\Pay\PaymeMethodsEnum;
use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class PaymeRequest extends FormRequest
{
    use ValidationException;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rulesMap = PaymeMethodsEnum::getRules();

        return [
            'jsonrpc' => [
                'nullable',
                'string'
            ],
            'id' => [
                'nullable',
                'integer'
            ],
            'method' => [
                'required',
                'string',
                new Enum(PaymeMethodsEnum::class),
                $this->getCustomRule($rulesMap)
            ],
            'params' => [
                'required',
                'array'
            ]
        ];
    }

    /**
     * @param array $rulesMap
     * @return mixed
     */
    private function getCustomRule(array $rulesMap): mixed
    {
        $param = $this->post('method');

        return $rulesMap[$param];
    }

}
