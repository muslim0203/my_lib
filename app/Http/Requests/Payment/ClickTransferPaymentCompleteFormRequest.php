<?php

namespace App\Http\Requests\Payment;

use App\Core\Enums\Pay\ClickActionCodeEnum;
use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ClickTransferPaymentCompleteFormRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'click_trans_id' => [
                'required',
                'integer'
            ],
            'service_id' => [
                'required',
                'integer'
            ],
            'click_paydoc_id' => [
                'required',
                'integer'
            ],
            'merchant_trans_id' => [
                'required',
                'string',
                Rule::exists('click_payments', 'id')
            ],
            'merchant_prepare_id' => [
                'required',
                'integer',
                Rule::exists('click_payments', 'id')
            ],
            'amount' => [
                'required',
                'string'
            ],
            'action' => [
                'required',
                'integer',
                Rule::in([ClickActionCodeEnum::CODE_COMPLETE->value])
            ],
            'error' => [
                'integer',
                'nullable'
            ],
            'error_note' => [
                'string',
                'nullable'
            ],
            'sign_time' => [
                'required',
                'date_format:Y-m-d H:i:s',
            ],
            'sign_string' => [
                'required',
                'string'
            ]
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['amount' => (string)$this->post('amount')]);
    }
}
