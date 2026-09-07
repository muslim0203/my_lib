<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Payme callback'lari uchun Basic autentifikatsiya.
 *
 * Ilgari sarlavha ko'r-ko'rona `substr(..., 6)` bilan kesilar, sxema
 * tekshirilmas va `explode(':')` natijasi ikkiga ajratilardi - ikki
 * nuqtali bo'lmagan sarlavha 500 xatosi berardi. Solishtirish ham
 * oddiy `!==` orqali bajarilardi.
 */
class PaymeAuthenticationMiddleware
{
    /**
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $login = (string)config('payme.login');
        $key = (string)config('payme.key');

        // Sozlanmagan integratsiya hech qachon "to'g'ri" hisoblanmaydi:
        // bo'sh sir bilan autentifikatsiya qilinmaydi.
        if ($login === '' || $key === '') {
            return self::response($request->post('id'));
        }

        $credentials = $this->credentials($request);

        if ($credentials === null) {
            return self::response($request->post('id'));
        }

        [$username, $password] = $credentials;

        // hash_equals vaqt bo'yicha xavfsiz solishtirish beradi.
        // Bitwise `&` ataylab ishlatilgan: `&&` dan farqli o'laroq u
        // qisqa tutashuv qilmaydi, ya'ni login noto'g'ri bo'lganda ham
        // parol solishtiruvi baribir bajariladi.
        $loginMatches = hash_equals($login, $username);
        $keyMatches = hash_equals($key, $password);

        if ((int)$loginMatches & (int)$keyMatches) {
            return $next($request);
        }

        return self::response($request->post('id'));
    }

    /**
     * Authorization sarlavhasidan login va parolni ajratadi.
     *
     * Buzuq sarlavha uchun istisno emas, `null` qaytariladi.
     *
     * @param Request $request
     * @return array{0: string, 1: string}|null
     */
    protected function credentials(Request $request): ?array
    {
        $header = (string)$request->header('Authorization', '');

        if ($header === '') {
            return null;
        }

        $parts = explode(' ', $header, 2);

        if (count($parts) !== 2 || strcasecmp($parts[0], 'Basic') !== 0) {
            return null;
        }

        // Qat'iy Base64: noto'g'ri belgilar jimgina tashlab yuborilmaydi.
        $decoded = base64_decode(trim($parts[1]), true);

        if ($decoded === false) {
            return null;
        }

        // Parol ichida ham ikki nuqta bo'lishi mumkin, shuning uchun
        // faqat birinchisi bo'yicha ajratiladi.
        $split = explode(':', $decoded, 2);

        if (count($split) !== 2) {
            return null;
        }

        return [$split[0], $split[1]];
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
