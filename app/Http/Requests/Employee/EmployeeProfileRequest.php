<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EmployeeProfileRequest extends FormRequest
{
    public $file = null;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'birth_date' => ['required', 'date'],
            'passport' => ['required', 'string'],
            'pin_fl' => ['required', 'string'],
            'current_address' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:jpeg,jpg,png', 'max:10240'],
        ];
    }

}
