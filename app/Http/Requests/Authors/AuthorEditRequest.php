<?php

namespace App\Http\Requests\Authors;

use App\Core\Enums\Genders\GenderEnum;
use App\Core\Helpers\Requests\ValidationException;
use App\Models\Users\User;
use App\Rules\Auth\EmailRegExRule;
use App\Rules\FilesRule;
use App\Rules\PassportRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class AuthorEditRequest extends FormRequest
{
    use ValidationException;

    protected ?int $userId = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::check()) {
            /**
             * @var User $user
             */
            $user = Auth::user();
            $this->userId = $user->getId();

            return true;
        }

        return false;
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
            'gender' => ['required', new Enum(GenderEnum::class)],
            'pin_fl' => ['required', 'integer', Rule::unique('authors', 'pin_fl')->ignore($this->userId, 'user_id')],
            'passport' => ['required', 'string', new PassportRule(), Rule::unique('authors', 'passport')->ignore($this->userId, 'user_id')],
            'email' => ['required', 'email', new EmailRegExRule(), Rule::unique('authors', 'email')->ignore($this->userId, 'user_id')],
            'phone' => ['required', 'string'],
            'academic_degree_id' => ['required', 'integer', Rule::exists('enum_academic_degrees', 'id')],
            'academic_position_id' => ['required', 'integer', Rule::exists('enum_academic_positions', 'id')],
            'education_type_id' => ['required', 'integer', Rule::exists('enum_education_types', 'id')],
            'description' => ['required', 'string'],
            'current_address' => ['required', 'string'],
            'file_id' => ['required', 'integer', Rule::exists('files', 'id')],
            'file_name' => ['required', 'string'],
            'diploma_file_id' => ['required', 'integer', Rule::exists('files', 'id')],
            'diploma_file_name' => ['required', 'string'],
            'licence_file_id' => ['required', 'integer', Rule::exists('files', 'id')],
            'licence_file_name' => ['required', 'string'],
            'extra_files' => ['array', new FilesRule()],
            'account_number' => ['required', 'string', Rule::unique('authors', 'account_number')->ignore($this->id)],
        ];
    }
}
