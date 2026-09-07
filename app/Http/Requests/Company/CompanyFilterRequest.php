<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CompanyFilterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title_uz'       => ['nullable', 'string'],
            'title_oz'       => ['nullable', 'string'],
            'title_ru'       => ['nullable', 'string'],
            'content_uz'       => ['nullable', 'string'],
            'content_oz'       => ['nullable', 'string'],
            'content_ru'       => ['nullable', 'string'],
        ];
    }
}
