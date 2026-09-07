<?php

namespace App\Http\Requests\Auth;

use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use App\Rules\GoogleReCaptcha;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_name
 * @property string $email
 * @property string $password
 */
class RegisterByEmailRequest extends FormRequest
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
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name'  => strip_tags(trim($this->first_name)),
            'last_name'   => strip_tags(trim($this->last_name)),
            'middle_name' => strip_tags(trim($this->middle_name)),
            'email'       => strip_tags(trim($this->email)),
            'password'    => strip_tags(trim($this->password)),
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name'       => ['required', 'string'],
            'last_name'        => ['required', 'string'],
            'middle_name'      => ['required', 'string'],
            'email'            => ['required', 'email', new EmailRegExRule(), Rule::unique('users', 'email')->ignore(2, 'status')],
            'password'         => ['required', Password::min(6)->mixedCase()->numbers(), 'same:confirm_password'],
            'confirm_password' => ['required', 'string', 'max:50', 'min:6'],
            'g_recaptcha'      => ['required', new GoogleReCaptcha()],
        ];
    }
}
