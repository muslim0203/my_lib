<?php

namespace App\Http\Requests\User;

use App\Core\Helpers\Requests\ValidationException;
use App\Rules\ProductGenreRule;
use App\Rules\ProductTypeRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SelectInterestRequest extends FormRequest
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
            'types' => ['bail', 'array', new ProductTypeRule(false)],
            'genres' => ['bail', 'array', new ProductGenreRule(false)],
        ];
    }
}
