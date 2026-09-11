<?php

namespace App\Http\Requests\Auth;

use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class LoginByGoogleRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:4096'],
            'state' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]{43}$/'],
            'code_verifier' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]{43}$/'],
        ];
    }
}
