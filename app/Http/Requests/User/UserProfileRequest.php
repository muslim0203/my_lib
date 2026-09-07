<?php

namespace App\Http\Requests\User;

use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

/**
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $middle_name
 * @property string|null $description
 * @property string|null $current_address
 */
class UserProfileRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users,username,' . Auth::id(),
            'password' => ['required', 'string', 'max:50', 'min:6', 'same:confirm_password'],
            'email' => ['nullable', 'email', new EmailRegExRule()],
            'phone' => ['nullable', 'string', 'max:255'],
        ];
    }
}
