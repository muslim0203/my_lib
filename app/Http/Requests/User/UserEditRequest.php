<?php

namespace App\Http\Requests\User;

use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $description
 * @property string|null $current_address
 */
class UserEditRequest extends FormRequest
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
            'first_name'      => trim(strip_tags($this->first_name)),
            'last_name'       => trim(strip_tags($this->last_name)),
            'middle_name'     => trim(strip_tags($this->middle_name)),
            'description'     => trim(strip_tags($this->description)),
            'current_address' => trim(strip_tags($this->current_address)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name'      => ['nullable', 'string'],
            'last_name'       => ['nullable', 'string'],
            'middle_name'     => ['nullable', 'string'],
            'current_address' => ['nullable', 'string'],
            'description'     => ['nullable', 'string'],
            'birth_date'      => ['nullable', 'string', 'date_format:d.m.Y'],
            'file_id'         => ['nullable', 'integer', Rule::exists('files', 'id')],
            'file_name'       => ['nullable', 'string'],
        ];
    }
}
