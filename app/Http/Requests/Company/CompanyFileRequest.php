<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CompanyFileRequest extends FormRequest
{
    public $file = null;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'company_id' => ['required', 'integer', 'exists:company,id'],
            'file_id' => ['integer', 'exists:files,id'],
            'file_name' => ['required', 'string'],
            'title_uz' => ['required', 'string'],
            'title_oz' => ['required', 'string'],
            'title_ru' => ['required', 'string'],
            'enabled' => ['boolean'],
            'file' => ['nullable', 'file', 'mimes:jpeg,jpg,png,pdf', 'max:20480'],
        ];
    }
}
