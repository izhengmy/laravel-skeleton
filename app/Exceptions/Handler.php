<?php

namespace App\Exceptions;

use App\Support\Http;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AdminBusinessException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            /** @noinspection PhpUndefinedMethodInspection */
            $status = $e->getStatusCode();
        } elseif ($this->isBusinessException($e)) {
            $status = 200;
        } elseif ($e instanceof ModelNotFoundException) {
            $status = 404;
        } else {
            $status = 500;
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return new JsonResponse(
            $this->convertExceptionToArray($e),
            $status,
            $this->isHttpException($e) ? $e->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $code = 422;
        $message = Http::MESSAGES[$code];
        $errors = $e->errors();

        foreach ($errors as $key => $error) {
            $errors[$key] = pangu(current($error));
        }

        return response()->json(compact('code', 'message', 'errors'), $code);
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Exception  $e
     * @return \Exception
     */
    protected function prepareException(Exception $e)
    {
        if (! $e instanceof ModelNotFoundException) {
            return parent::prepareException($e);
        }

        $model = $e->getModel();

        if (! defined("{$model}::RESOURCE_NAME")) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        return $e;
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        $code = 401;
        $message = Http::MESSAGES[$code];

        return response()->json(compact('code', 'message'), $code);
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Exception  $e
     * @return array
     */
    protected function convertExceptionToArray(Exception $e)
    {
        $debug = config('app.debug');

        if ($e instanceof ModelNotFoundException) {
            $model = $e->getModel();
            $code = 404;
            $message = $model::RESOURCE_NAME.'不存在';
        } elseif ($this->isHttpException($e)) {
            /** @noinspection PhpUndefinedMethodInspection */
            $code = $e->getStatusCode();
            $message = Http::MESSAGES[$code] ?? $e->getMessage();
        } elseif ($this->isBusinessException($e)) {
            $code = $e->getCode();
            $message = $e->getMessage();
        } else {
            $code = 500;
            $message = Http::MESSAGES[$code];
        }

        return $debug ? [
            'code' => $code,
            'message' => $message,
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => collect($e->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ] : [
            'code' => $code,
            'message' => $message,
        ];
    }

    /**
     * Determine if the given exception is an Business exception.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function isBusinessException(Exception $e)
    {
        return $e instanceof AdminBusinessException;
    }
}
