<?php

namespace App\Core\Helpers\Requests;

use App\Core\Helpers\Response\Validation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidationException
{
    /**
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(Validation::send($validator));
    }
}
