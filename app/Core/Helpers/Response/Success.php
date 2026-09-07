<?php

namespace App\Core\Helpers\Response;

use Illuminate\Http\JsonResponse;

class Success
{
    /**
     * @param string|null $message
     * @param array|object|null $data
     * @return JsonResponse
     */
    public static function send(
        ?string           $message = null,
        array|object|null $data = null
    ): JsonResponse
    {
        $message = is_null($message) ? 'success' : $message;

        return response()->json([
            'success' => true,
            'message' => __('client.' . $message),
            'data'    => $data
        ]);
    }

    /**
     * @param string|null $message
     * @param array|object|null $data
     * @return JsonResponse
     */
    public static function error(
        ?string           $message = null,
        array|object|null $data = null
    ): JsonResponse
    {
        $message = is_null($message) ? 'success' : $message;

        return response()->json([
            'success' => false,
            'message' => __('client.' . $message),
            'data'    => $data
        ]);
    }
}
