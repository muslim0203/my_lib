<?php

namespace App\Http\Requests\Products;

use App\Core\Enums\ProductPriceTypeEnum;
use App\Core\Helpers\Requests\ValidationException;
use App\Core\Repository\Enum\CategoriesRepository;
use App\Core\Repository\Product\ProductPriceTypeRepository;
use App\Rules\FileExistsRule;
use App\Rules\ProductCategoryRule;
use App\Rules\ProductGenreRule;
use App\Rules\ProductTagRule;
use App\Rules\ProductTypeRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProductCreateRequest extends FormRequest
{
    use ValidationException;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title_oz' => trim(strip_tags($this->input('title_oz'))),
            'title_uz' => trim(strip_tags($this->input('title_uz'))),
            'title_ru' => trim(strip_tags($this->input('title_ru'))),
            'description_oz' => trim(strip_tags($this->input('description_oz'))),
            'description_uz' => trim(strip_tags($this->input('description_uz'))),
            'description_ru' => trim(strip_tags($this->input('description_ru'))),
            'extra_authors' => trim(strip_tags($this->input('extra_authors'))),
            'is_agree' => true
        ]);

        if (intval($this->post('price_type_id')) !== ProductPriceTypeEnum::FREE->value) {
            $priceType = (new ProductPriceTypeRepository())->getById($this->integer('price_type_id'));
            $this->merge([
                'price_value' => (string)($this->input('price_value') * ($priceType->getPercentage() / 100) + $this->input('price_value')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title_oz' => ['nullable', 'string', 'max:255'],
            'title_uz' => ['nullable', 'string', 'max:255'],
            'title_ru' => ['nullable', 'string', 'max:255'],
            'is_download' => ['nullable', 'boolean'],
            'description_oz' => ['nullable', 'string'],
            'description_uz' => ['nullable', 'string'],
            'description_ru' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'integer', 'exists:products,id'],
            'size' => ['required', 'string'],
            'wrapper_file_id' => ['bail', 'required', 'integer', 'exists:files,id'],
            'wrapper_file_name' => ['required', 'string'],
            'source_file_id' => ['bail', 'required', 'integer', 'exists:files,id'],
            'source_file_name' => ['required', 'string'],
            'price_type_id' => ['bail', 'required', 'integer', 'exists:product_price_types,id'],
            'price_value' => [
                Rule::requiredIf(
                    fn() => intval($this->post('price_type_id')) !== ProductPriceTypeEnum::FREE->value,
                ),
                'nullable',
                'numeric'
            ],
            'create_date' => ['required', 'string', 'date_format:Y-m-d'],
            'last_update_date' => ['nullable', 'string', 'date_format:Y-m-d'],
            'extra_authors' => ['nullable', 'string'],
            'discount' => ['nullable', 'integer', 'between:1,100'],
            'from_expire_at' => ['nullable', 'date', 'date_format:Y-m-d'],
            'to_expire_at' => ['nullable', 'date', 'date_format:Y-m-d'],
            'genres' => ['bail', 'required', 'array', new ProductGenreRule()],
            'types' => ['bail', 'required', 'array', new ProductTypeRule()],
            'tags' => ['bail', 'required', 'array', new ProductTagRule()],
            'categories' => ['bail', 'required', 'array', new ProductCategoryRule()],
            'is_agree' => ['required', 'boolean', Rule::in([true])],
            'audio_files' => ['bail', 'nullable', 'array', new FileExistsRule()],
            'licence_file_id' => [
                'nullable',
                'array',
                new FileExistsRule()
            ],
            'licence_date' => [
                Rule::requiredIf(
                    (new CategoriesRepository())->existsHasExtraColumnRequire($this->post('categories'))
                ),
                'nullable',
                'date_format:Y-m-d'
            ],
            'licence_code' => [
                Rule::requiredIf(
                    (new CategoriesRepository())->existsHasExtraColumnRequire($this->post('categories'))
                ),
                'nullable',
                'string',
            ]
        ];
    }
}
