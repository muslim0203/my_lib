<?php

namespace App\Http\Middleware;

use App\Core\Enums\LanguageEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $local = $request->hasHeader('Accept-Language')
            ? $request->header('Accept-Language')
            : LanguageEnum::_OZ->value;

        if (!in_array($local, [
            LanguageEnum::_OZ->value,
            LanguageEnum::_RU->value,
            LanguageEnum::_UZ->value,
        ])) {
            $local = LanguageEnum::_OZ->value;
        }

        app()->setLocale($local);

        return $next($request);
    }

}
