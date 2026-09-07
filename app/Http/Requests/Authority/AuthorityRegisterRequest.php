<?php

namespace App\Http\Requests\Authority;

use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @property string $name_oz
 * @property string $name_uz
 * @property string $name_ru
 * @property string $address
 * @property string $description
 * @property string $phone
 */
class AuthorityRegisterRequest extends FormRequest
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
            'name_oz' => trim(strip_tags($this->name_oz)),
            'name_uz' => trim(strip_tags($this->name_uz)),
            'name_ru' => trim(strip_tags($this->name_ru)),
            'address' => trim(strip_tags($this->address)),
            'phone' => trim(strip_tags($this->phone)),
            'description' => trim(strip_tags($this->description)),
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
            'name_oz' => ['required', 'string', 'max:255'],
            'name_uz' => ['required', 'string', 'max:255'],
            'name_ru' => ['required', 'string', 'max:255'],
            'inn' => ['required', 'integer', 'digits:9', Rule::unique('authority', 'inn')->ignore($this->id)],
            'account_number' => ['required', 'numeric', Rule::unique('authority', 'account_number')->ignore($this->id)],
            'email' => ['required', 'string', 'email', 'max:50', Rule::unique('authority', 'email')->ignore($this->id), new EmailRegExRule()],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'is_agree' => ['required', 'boolean', Rule::in([true])],
            'activity_type_id' => ['required', 'integer', 'exists:enum_activity_types,id'],
            'certificate_file_id' => ['required', 'integer', 'exists:files,id'],
            'certificate_file_name' => ['required', 'string', 'max:255'],
            'patent_file_id' => ['required', 'integer', 'exists:files,id'],
            'patent_file_name' => ['required', 'string', 'max:255'],

            'activity_sphere_ids' => ['array'],
            'description' => ['nullable', 'string'],
            'activity_sphere_ids.*' => ['integer', 'exists:enum_categories,id'],
            'file_id' => ['nullable', 'integer', 'exists:files,id'],
            'file_name' => ['nullable', 'string', 'max:255'],
        ];
    }
}
