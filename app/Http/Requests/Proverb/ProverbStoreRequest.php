<?php

namespace App\Http\Requests\Proverb;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProverbStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'author_oz' => 'string|required',
            'author_uz' => 'string|required',
            'author_ru' => 'string|required',
            'content_oz' => 'string|required',
            'content_uz' => 'string|required',
            'content_ru' => 'string|required',
        ];
    }
}
