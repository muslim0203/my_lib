<?php

namespace App\Http\Requests\Auth;

use App\Core\Enums\Users\UserStatusEnum;
use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property string $token
 */
class VerifyMailTokenRequest extends FormRequest
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
            'token' => strip_tags(trim($this->token))
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'token' => [
                'required',
                'min:25',
                'max:25',
                Rule::exists('users_verify_mail_tokens', 'token')
            ],
            'email' => [
                'required',
                'string',
                'email',
                new EmailRegExRule(),
                Rule::exists('users', 'email')->where('status', UserStatusEnum::_UN_CONFIRMED->value)
            ]
        ];
    }
}
