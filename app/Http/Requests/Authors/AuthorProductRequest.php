<?php

namespace App\Http\Requests\Authors;

use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class AuthorProductRequest extends formrequest
{
    use ValidationException;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author_id' => 'required|integer|exists:users,id',
            'type_id' => 'required|integer|exists:enum_product_types,id',
        ];
    }
}
