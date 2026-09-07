<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymeAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('Authorization') === false) {
            return self::response($request->post('id'));
        }

        $credentials = base64_decode(substr($request->header('Authorization'), 6));
        list($username, $password) = explode(':', $credentials);

        if ($username !== config('payme.login') || $password !== config('payme.key')) {
            // Provided username or password does not match, throw an exception
            // Alternatively, the login prompt can be displayed once more
            return self::response($request->post('id'));
        }

        return $next($request);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    protected static function response($id = null): JsonResponse
    {
        return response()->json([
            'error' => [
                'code' => -32504,
                'message' => 'Authentication failed',
            ],
            'id' => $id
        ]);
    }
}
