<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'title_uz'   => ['required', 'string'],
            'title_oz'   => ['required', 'string'],
            'title_ru'   => ['required', 'string'],
            'content_uz'   => ['required', 'string'],
            'content_oz'   => ['required', 'string'],
            'content_ru'   => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
            'enabled'     => ['boolean'],
        ];
    }
}
