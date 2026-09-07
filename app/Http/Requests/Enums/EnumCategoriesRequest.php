<?php

namespace App\Http\Requests\Enums;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class EnumCategoriesRequest extends FormRequest
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
            'name_uz' => ['required', 'string'],
            'name_oz' => ['required', 'string'],
            'name_ru' => ['required', 'string'],
            'parent_id' => ['nullable', 'integer'],
            'sort' => ['nullable','integer','default:0'],
            'enabled' => ['required', 'integer'],
            'has_extra_column_require' => ['required', 'boolean']
        ];
    }
}
