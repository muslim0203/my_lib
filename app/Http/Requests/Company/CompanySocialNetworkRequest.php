<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\File;

class CompanySocialNetworkRequest extends FormRequest
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
            'name_uz' => ['required', 'string'],
            'name_oz' => ['required', 'string'],
            'name_ru' => ['required', 'string'],
            'link' => ['required', 'string'],
            'company_id' => ['required', 'string'],
            'enabled' => ['boolean'],
            'file' => [
                'file',
                'required',
                File::types(['png','jpg','jpeg'])->max(10 * 1024)
            ]
        ];
    }
}
