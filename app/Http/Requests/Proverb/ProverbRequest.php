<?php

namespace App\Http\Requests\Proverb;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProverbRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'author_oz' => 'string|nullable',
            'author_uz' => 'string|nullable',
            'author_ru' => 'string|nullable',
        ];
    }
}
