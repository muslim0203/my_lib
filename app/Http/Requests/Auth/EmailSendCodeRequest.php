<?php

namespace App\Http\Requests\Auth;

use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use Illuminate\Foundation\Http\FormRequest;

class EmailSendCodeRequest extends FormRequest
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
            'email' => trim(strip_tags($this->post('email'))),
        ]);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', new EmailRegExRule()],
        ];
    }
}
