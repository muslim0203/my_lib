<?php

namespace App\Http\Requests\Enums;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class EnumCategoriesFilterRequest extends FormRequest
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
            'name_ru'       => ['nullable', 'string']
        ];
    }


    public function messages()
    {
        return [
            'name_uz.required'      => Lang::get('enum_categories.name') . ' ' . Lang::get('messages.required'),
            'name_uz.unique'      => Lang::get('enum_categories.name') . ' ' . Lang::get('messages.unique'),
            'name_oz.required'      => Lang::get('enum_categories.name') . ' ' . Lang::get('messages.required'),
            'name_oz.unique'      => Lang::get('enum_categories.name') . ' ' . Lang::get('messages.unique'),
            'name_ru.required'      => Lang::get('enum_categories.name') . ' ' . Lang::get('messages.required'),
            'name_ru.unique'      => Lang::get('enum_categories.name') . ' ' . Lang::get('messages.unique'),
        ];
    }
}
