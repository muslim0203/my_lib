<?php

namespace App\Http\Requests\Authors;

use App\Core\Enums\Genders\GenderEnum;
use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use App\Rules\FilesRule;
use App\Rules\PassportRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $description
 * @property string $current_address
 */
class AuthorRegisterRequest extends FormRequest
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
            'first_name' => strip_tags(trim($this->first_name)),
            'last_name' => strip_tags(trim($this->last_name)),
            'middle_name' => strip_tags(trim($this->middle_name)),
            'description' => strip_tags(trim($this->description)),
            'current_address' => strip_tags(trim($this->current_address)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'birthdate' => ['required', 'date_format:Y-m-d'],
            'pin_fl' => ['required', 'integer', Rule::unique('authors', 'pin_fl')],
            'passport' => ['required', 'string', new PassportRule(), Rule::unique('authors', 'passport')],
            'passport_given_date' => ['required', 'date_format:Y-m-d'],
            'passport_given_place' => ['required', 'string'],
            'email' => ['required', 'email', new EmailRegExRule(), Rule::unique('authors', 'email')],
            'phone' => ['required', 'string'],
            'account_number' => ['required', 'numeric', Rule::unique('authors', 'account_number')],

            'gender' => ['nullable', new Enum(GenderEnum::class)],
            'academic_degree_id' => ['nullable', 'integer', Rule::exists('enum_academic_degrees', 'id')],
            'academic_position_id' => ['nullable', 'integer', Rule::exists('enum_academic_positions', 'id')],
            'education_type_id' => ['nullable', 'integer', Rule::exists('enum_education_types', 'id')],
            'description' => ['nullable', 'string'],
            'current_address' => ['nullable', 'string'],
            'file_id' => ['nullable', 'integer', Rule::exists('files', 'id')],
            'file_name' => ['nullable', 'string'],
            'diploma_file_id' => ['nullable', 'integer', Rule::exists('files', 'id')],
            'diploma_file_name' => ['nullable', 'string'],
            'licence_file_id' => ['nullable', 'integer', Rule::exists('files', 'id')],
            'licence_file_name' => ['nullable', 'string'],
            //'extra_files' => ['nullable', 'array', new FilesRule()],
            'is_agree' => ['required', 'boolean', Rule::in([true])],
            'work_place' => ['nullable', 'string'],
            'position' => ['nullable', 'string'],
            'inn' => ['nullable', 'integer', 'digits:9', Rule::unique('authority', 'inn')->ignore($this->id)],
        ];
    }
}
