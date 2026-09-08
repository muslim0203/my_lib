<?php

namespace App\Core\Helpers\Response;

use Illuminate\Http\JsonResponse;

class Success
{
    /**
     * Muvaffaqiyatli javob.
     *
     * @param string|null $message `client.` guruhidan tarjima kaliti
     * @param array|object|null $data
     * @param int $code
     * @return JsonResponse
     */
    public static function send(
        ?string           $message = null,
        array|object|null $data = null,
        int               $code = 200
    ): JsonResponse
    {
        $message = is_null($message) ? 'success' : $message;

        return ApiResponse::make(true, __('client.' . $message), $data, $code);
    }

    /**
     * Xato javobi.
     *
     * Ilgari bu metod 200 holat kodi bilan qaytarardi va chaqiruvchi
     * `->setStatusCode(400)` qo'shishi kerak edi. Endi kod bu yerda
     * beriladi va javob tanasidagi `code` bilan har doim mos keladi.
     *
     * @param string|null $message
     * @param array|object|null $data
     * @param int $code
     * @return JsonResponse
     */
    public static function error(
        ?string           $message = null,
        array|object|null $data = null,
        int               $code = 400
    ): JsonResponse
    {
        $message = is_null($message) ? 'error' : $message;

        return ApiResponse::make(false, __('client.' . $message), $data, $code);
    }
}
