<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Core\Helpers\Response\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (TokenInvalidException $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return self::response('Token is Invalid');
        } catch (TokenExpiredException $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return self::response('Token is Expire');
        } catch (Exception $e) {
            Log::error($e->getMessage(), $e->getTrace());
            return self::response('Authorization Token not found');
        }

        return $next($request);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    protected static function response(string $message): JsonResponse
    {
        // Umumiy API konverti (App\Core\Helpers\Response\ApiResponse):
        // mijoz barcha javoblarni bitta modelga parse qila oladi.
        return ApiResponse::make(false, __('client.' . $message), null, 401);
    }
}
