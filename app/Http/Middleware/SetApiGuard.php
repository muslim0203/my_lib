<?php

namespace App\Http\Middleware;

use App\Core\Enums\Auth\GuardsEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetApiGuard
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Auth::shouldUse(GuardsEnum::_MAIL->value);

        return $next($request);
    }
}
