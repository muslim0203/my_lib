<?php

namespace App\Http\Requests\Report;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductOrderSearchRequest extends FormRequest
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
            'title' => ['nullable', 'string'],
            'full_name' => ['nullable', 'string'],
            'product_name' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:enum_categories,id'],
            'genre_id' => ['nullable', 'integer', 'exists:enum_product_genres,id'],
            'tag_id' => ['nullable', 'integer', 'exists:enum_product_tags,id'],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date'],
        ];
    }
}
