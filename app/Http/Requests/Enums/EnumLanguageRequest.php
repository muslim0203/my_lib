<?php

namespace App\Http\Requests\Enums;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class EnumLanguageRequest extends FormRequest
{

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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name_oz' => ['required', 'string'],
            'name_uz' => ['required', 'string'],
            'name_ru' => ['required', 'string'],
            'enabled' => ['required', 'boolean']
        ];
    }
}
