<?php

namespace App\Core\Helpers\Response;

use Illuminate\Http\JsonResponse;

/**
 * API javoblarining yagona konverti.
 *
 * Ilgari uchta turli shakl bor edi: muvaffaqiyatda `success`, xatoda
 * `status`, validatsiyada esa `data` massiv ko'rinishida. Mijoz qaysi
 * holatda qaysi kalitni tekshirishni bilishi kerak edi.
 *
 * Endi HAR BIR javob bir xil beshta kalitdan iborat, shuning uchun
 * mijoz bitta modelga parse qila oladi:
 *
 *   {
 *     "success": true|false,
 *     "message": "...",
 *     "code": 200,
 *     "data": {...} | [...] | null,
 *     "errors": {"email": ["..."]} | null
 *   }
 */
class ApiResponse
{
    /**
     * @param bool $success
     * @param string|null $message
     * @param mixed $data
     * @param int $code HTTP holat kodi (tanada ham takrorlanadi)
     * @param array<string, array<int, string>>|null $errors Maydon nomi => xabarlar
     * @return JsonResponse
     */
    public static function make(
        bool    $success,
        ?string $message = null,
        mixed   $data = null,
        int     $code = 200,
        ?array  $errors = null
    ): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => (string)$message,
            'code'    => $code,
            'data'    => $data,
            'errors'  => $errors,
        ], $code);
    }
}
