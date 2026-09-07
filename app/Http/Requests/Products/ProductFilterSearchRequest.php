<?php

namespace App\Http\Requests\Products;

use App\Core\Enums\Products\ProductCategoryEnum;
use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductFilterSearchRequest extends FormRequest
{
    use ValidationException;

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category' => ['nullable', 'string', Rule::in(ProductCategoryEnum::getList())],
            'category_id' => ['nullable', 'integer', 'exists:enum_categories,id'],
            'title' => ['nullable', 'string'],
        ];
    }
}
