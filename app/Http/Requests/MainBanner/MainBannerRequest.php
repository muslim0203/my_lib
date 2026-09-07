<?php

namespace App\Http\Requests\MainBanner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MainBannerRequest extends FormRequest
{
    public array $banner_files = [];

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
            'name_uz' => ['required', 'string'],
            'name_oz' => ['required', 'string'],
            'name_ru' => ['required', 'string'],
            'content_uz' => ['required', 'string'],
            'content_oz' => ['required', 'string'],
            'content_ru' => ['required', 'string'],
            'link' => ['nullable', 'string'],
            'enabled' => ['boolean'],
            'is_view_content' => ['boolean'],
            'banner_files' => ['bail', 'array'],
        ];
    }
}
