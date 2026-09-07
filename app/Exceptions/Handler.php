<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * @param $request
     * @param Throwable $e
     * @return Response|JsonResponse|RedirectResponse|ResponseAlias
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|RedirectResponse|ResponseAlias
    {
        if ($this->wantsJsonResponse($request)) {
            return $e instanceof ValidationException
                ? $this->renderValidationException($e)
                : $this->renderApiException($e);
        }

        return parent::render($request, $e);
    }

    /**
     * Decide whether the client expects the JSON API envelope.
     *
     * The previous implementation compared the Content-Type header with the
     * exact string "application/json" (so "application/json; charset=utf-8"
     * did not match) and read the prefix of the *matched* route (which is
     * null for a 404, so API 404s produced an HTML error page).
     *
     * @param $request
     * @return bool
     */
    private function wantsJsonResponse($request): bool
    {
        $routePrefix = Route::current()?->getPrefix();

        if (!empty($routePrefix) && trim($routePrefix, '/') === 'api') {
            return true;
        }

        $contentType = strtolower((string) $request->header('content-type'));

        if (str_contains($contentType, 'application/json')) {
            return true;
        }

        return $request->is('api/*') || $request->expectsJson();
    }

    /**
     * 422 + per field errors, instead of the previous 500 with an empty body.
     *
     * @param ValidationException $e
     * @return JsonResponse
     */
    private function renderValidationException(ValidationException $e): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'message' => $e->getMessage(),
            'code'    => ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
            'data'    => [
                'errors' => $e->errors(),
            ],
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Throwable $e
     * @return JsonResponse
     */
    private function renderApiException(Throwable $e): JsonResponse
    {
        $debug = (bool) config('app.debug');

        // Local, never an instance property: a status code from one request
        // must never leak into the next one (the handler is a singleton).
        $statusCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
        $code       = $e->getCode();
        $message    = $e->getMessage();

        if ($e instanceof AuthenticationException) {
            $statusCode = ResponseAlias::HTTP_UNAUTHORIZED;
            $message    = __('client.Unauthorized');
        } elseif ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
            $statusCode = ResponseAlias::HTTP_FORBIDDEN;
            $message    = __('client.Unauthorized action.');
        } elseif ($e instanceof ModelNotFoundException) {
            $statusCode = ResponseAlias::HTTP_NOT_FOUND;
            $message    = $this->modelNotFoundMessage($e, $debug);
        } elseif ($e instanceof QueryException) {
            // Never expose SQL / bindings to the client unless debugging.
            // Logging already happened in report() via the reportable callback.
            $statusCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;
            $message    = $debug ? $e->getMessage() : __('client.Query exception');
            $code       = $debug ? $code : $statusCode;
        } elseif ($e instanceof HttpExceptionInterface) {
            $statusCode = $e->getStatusCode();

            if ($message === '') {
                $message = ResponseAlias::$statusTexts[$statusCode] ?? __('client.Unknown error');
            }
        } else {
            // Unexpected throwable: the message may contain file paths,
            // credentials or internal class names. It is already written to
            // the log by report(); it must not be shipped to the client.
            if (!$debug) {
                $message = __('client.Unknown error');
                $code    = $statusCode;
            }
        }

        return response()->json([
            'status'  => false,
            'message' => $message,
            'code'    => $code,
            'data'    => [],
        ], $statusCode);
    }

    /**
     * The old implementation always exposed the internal Eloquent class name.
     *
     * @param ModelNotFoundException $e
     * @param bool $debug
     * @return string
     */
    private function modelNotFoundMessage(ModelNotFoundException $e, bool $debug): string
    {
        if (!$debug) {
            return __('client.Data is not found');
        }

        try {
            $model = (string) $e->getModel();

            if ($model === '') {
                return __('client.Data is not found');
            }

            $parts = explode('\\', $model);
            $short = (string) end($parts);

            return implode(' ', preg_split('/(?<=\w)(?=[A-Z])/', $short)) . ' is not found';
        } catch (Throwable $ignored) {
            return __('client.Data is not found');
        }
    }

    /**
     * @param QueryException $e
     * @return void
     */
    private function logQueryException(QueryException $e): void
    {
        // Bindings are intentionally NOT logged: they may hold credentials.
        Log::error('QueryException: ' . $e->getMessage(), [
            'sql'       => $e->getSql(),
            'exception' => $e,
        ]);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (QueryException $e) {
            $this->logQueryException($e);

            // Returning false stops the default logger so the exception is
            // written exactly once (with the SQL context added above).
            return false;
        });
    }
}
