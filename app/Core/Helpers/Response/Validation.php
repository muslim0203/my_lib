<?php

namespace App\Core\Helpers\Response;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class Validation
{
    /**
     * Validatsiya xatosi.
     *
     * Ilgari xatolar `data` ichida `[{field, message}]` massivi sifatida
     * qaytarilardi va statik xususiyatda to'planardi. Endi ular umumiy
     * konvertdagi `errors` maydonida, Laravel beradigan tabiiy shaklda:
     * `{"maydon": ["xabar", ...]}`.
     *
     * @param Validator $validator
     * @return JsonResponse
     */
    public static function send(Validator $validator): JsonResponse
    {
        return ApiResponse::make(
            false,
            __('client.Validation error'),
            null,
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
            $validator->errors()->messages()
        );
    }
}
