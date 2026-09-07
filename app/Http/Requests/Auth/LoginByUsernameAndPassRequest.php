<?php

namespace App\Http\Requests\Auth;

use App\Core\Enums\Auth\LoginTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class LoginByUsernameAndPassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'username' => [
                'required',
                'string',
                Rule::exists('users', 'username')->where('login_type', LoginTypeEnum::_LOGIN_LOGIN_PASS->value)
            ],
            'password' => ['required', 'string', Password::min(6)->mixedCase()->numbers()]
        ];
    }
}
