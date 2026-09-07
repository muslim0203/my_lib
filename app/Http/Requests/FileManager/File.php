<?php

namespace App\Http\Requests\FileManager;

use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class File extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                \Illuminate\Validation\Rules\File::types(config('filesystems.allow_extensions'))
                    ->max(config('filesystems.max_upload_size')) // 70 MB
            ]
        ];
    }
}
