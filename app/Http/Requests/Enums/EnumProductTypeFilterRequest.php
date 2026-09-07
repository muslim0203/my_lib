<?php

namespace App\Http\Requests\Enums;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EnumProductTypeFilterRequest extends FormRequest
{
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name_uz'    => ['nullable', 'string'],
            'name_oz'    => ['nullable', 'string'],
            'name_ru'    => ['nullable', 'string'],
            'enabled'    => ['nullable', 'boolean'],
        ];
    }
}
