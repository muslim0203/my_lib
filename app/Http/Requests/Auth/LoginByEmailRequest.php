<?php

namespace App\Http\Requests\Auth;

use App\Core\Helpers\Requests\ValidationException;
use App\Rules\Auth\EmailRegExRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginByEmailRequest extends FormRequest
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
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strip_tags(trim($this->post('email'))),
            'code' => strip_tags(trim($this->post('code'))),
        ]);
    }

    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            // `exists` qoidalari olib tashlandi: ular qaysi pochta
            // ro'yxatdan o'tganini va qaysi kod amalda ekanini oshkor
            // qilardi. Kodning to'g'riligi endi faqat autentifikatsiya
            // qatlamida, bir xil umumiy xato bilan tekshiriladi.
            'email' => ['required', 'string', 'email', new EmailRegExRule()],
            'code' => ['required', 'integer', 'digits:6']
        ];
    }
}
