<?php

namespace App\Exceptions;

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
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    private int $statusCode = 500;

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
        $routePrefix = Route::current()?->getPrefix();
        $contentType = $request->header('content-type');

        if ($contentType === 'application/json' || (!empty($routePrefix) && $routePrefix === 'api')) {

            if (!($e instanceof ValidationException)) {

                $message = $e->getMessage();

                /**
                 * @var HttpException $e
                 */
                if ($this->isHttpException($e)) {
                    $this->statusCode = $e->getStatusCode();
                }

                if ($e instanceof ModelNotFoundException) {
                    $this->statusCode = ResponseAlias::HTTP_NOT_FOUND;

                    try {
                        if (!empty($e->getModel())) {
                            $models = explode('\\', $e->getModel());
                            if (is_array($models)) {
                                $message = implode(' ', preg_split('/(?<=\\w)(?=[A-Z])/', $models[count($models) - 1])) . ' is not found';
                            }
                        }
                    } catch (\Exception $e) {
                        $message = __('client.Data is not found');
                    }
                }

                if ($e instanceof QueryException && config('app.env') === 'production') {
                    Log::error($e->getMessage());
                    $message = __('client.Query exception');
                }

                return response()->json([
                    'status'  => false,
                    'message' => $message,
                    'code'    => $e->getCode(),
                    'data'    => []
                ], $this->statusCode);
            }

            return \response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'data'    => []
            ])->setStatusCode(ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $e);
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (QueryException $e) {

        });
    }
}
