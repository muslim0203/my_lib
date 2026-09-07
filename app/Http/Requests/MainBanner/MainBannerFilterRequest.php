<?php

namespace App\Http\Requests\MainBanner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MainBannerFilterRequest extends FormRequest
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
            'name_uz'       => ['nullable', 'string'],
            'name_oz'       => ['nullable', 'string'],
            'name_ru'       => ['nullable', 'string'],
            'content_uz'       => ['nullable', 'string'],
            'content_oz'       => ['nullable', 'string'],
            'content_ru'       => ['nullable', 'string'],
        ];
    }
}
