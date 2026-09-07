<?php

namespace App\Http\Requests\Reports;

use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PurchaseFormRequest extends FormRequest
{
    use ValidationException;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'report_type_id' => [
                'nullable',
                'integer',
                Rule::exists('report_types', 'id')
            ],
            'from_date' => [
                'nullable',
                'date_format:Y-m-d'
            ],
            'to_date' => [
                'nullable',
                'date_format:Y-m-d'
            ]
        ];
    }
}
