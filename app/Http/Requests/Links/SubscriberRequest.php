<?php

namespace App\Http\Requests\Links;

use App\Core\Helpers\Requests\ValidationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubscriberRequest extends FormRequest
{

    use ValidationException;

    public bool $subscribe = false;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'author_id' => ['required', 'integer',Rule::exists('users', 'id')],
            'subscribe' => ['required', 'boolean'],
        ];
    }

}
