<?php

namespace Jaspur\LocalizedExceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\LockedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Throwable;

class LocalizedExceptionHandler extends BaseHandler
{
    protected $httpExceptionMap = [
        AuthenticationException::class => 401,
        AuthorizationException::class => 403,
        NotFoundHttpException::class => 404,
        MethodNotAllowedHttpException::class => 405,
        NotAcceptableHttpException::class => 406,
        ConflictHttpException::class => 409,
        GoneHttpException::class => 410,
        LengthRequiredHttpException::class => 411,
        PreconditionFailedHttpException::class => 412,
        PreconditionRequiredHttpException::class => 428,
        TooManyRequestsHttpException::class => 429,
        LockedHttpException::class => 423,
        UnauthorizedHttpException::class => 401,
        ServiceUnavailableHttpException::class => 503,
        UnsupportedMediaTypeHttpException::class => 415,
        UnprocessableEntityHttpException::class => 422,
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function render($request, Throwable $exception): Response
    {
        foreach ($this->httpExceptionMap as $exceptionClass => $statusCode) {
            if ($exception instanceof $exceptionClass) {
                return $this->buildResponse($request, $exception, $statusCode);
            }
        }

        if ($exception instanceof ValidationException) {
            return $this->buildValidationResponse($request, $exception);
        }

        if ($exception instanceof HttpException) {
            return $this->buildResponse($request, $exception, $exception->getStatusCode());
        }

        return $this->buildResponse($request, $exception, 500);
    }

    /**
     * Build the HTTP response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    protected function buildResponse($request, Throwable $exception, int $statusCode): Response
    {
        $message = __($exception->getMessage()) ?: __("jaspur::http-statuslist.{$statusCode}");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'status' => $statusCode,
            ], $statusCode);
        }

        return response(view("errors.{$statusCode}", [
            'code' => $statusCode,
            'message' => $message,
            'exception' => $exception,
        ]), $statusCode);
    }

    /**
     * Build the HTTP response for validation errors.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    protected function buildValidationResponse($request, ValidationException $exception): Response
    {
        $statusCode = 422;
        $errors = $exception->errors();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => __("jaspur::http-statuslist.{$statusCode}"),
                'errors' => $errors,
                'status' => $statusCode,
            ], $statusCode);
        }

        return response(view('errors::validation', [
            'code' => $statusCode,
            'errors' => $errors,
            'exception' => $exception,
        ]), $statusCode);
    }
}
