<?php

namespace Jaspur\LocalizedExceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Illuminate\Support\Facades\View;
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
     * Renders a response based on the given exception.
     *
     * This method checks the type of the exception and returns an appropriate
     * response. It first checks if the exception matches any class in the
     * httpExceptionMap and uses the corresponding status code. If the exception
     * is a ValidationException, it builds a validation response. If it is an
     * HttpException, it uses the exception's status code. For all other cases,
     * it defaults to a status code of 500.
     *
     * @param  mixed  $request  The current request instance.
     * @param  Throwable  $exception  The exception to render a response for.
     * @return Response The constructed response object.
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
     * Builds a response based on the request type and exception.
     *
     * This function generates a JSON response if the request expects JSON,
     * otherwise it generates an HTML response using a view template.
     *
     * @param  mixed  $request  The incoming request object.
     * @param  Throwable  $exception  The exception that was thrown.
     * @param  int  $statusCode  The HTTP status code for the response.
     * @return Response The generated response object.
     */
    protected function buildResponse($request, Throwable $exception, int $statusCode): Response
    {
        $message = __(key: $exception->getMessage()) ?: __(key: "jaspur::http-statuslist.{$statusCode}");

        if ($request->wantsJson()) {
            return response()->json(data: [
                'message' => $message,
                'status' => $statusCode,
            ], status: $statusCode);
        }

        return response(content: view($this->getResponseView($statusCode), data: [
            'code' => $statusCode,
            'message' => $message,
            'exception' => $exception,
        ]), status: $statusCode);
    }


    /**
     * Retrieves the appropriate response view based on the given status code.
     *
     * This method checks for the existence of specific error views corresponding
     * to the provided status code. It searches for views in the format of 
     * "errors.{statusCode}" and "errors::{statusCode}". If a matching view is 
     * found, it is returned. If no specific view is found, a default view 
     * 'errors::minimal' is returned.
     *
     * @param int|string $statusCode The status code used to determine the response view.
     * @return string The name of the view to be used for the response.
     */
    private function getResponseView(int|string $statusCode): string
    {
        foreach (["errors.$statusCode", "errors::$statusCode"] as $view) {
            if (View::exists($view) || file_exists(resource_path("views/errors/$statusCode.blade.php"))) {
                return $view;
            }
        }

        return 'errors::minimal';
    }

    /**
     * Build a validation response for a given request and validation exception.
     *
     * This method generates an appropriate HTTP response based on the request's
     * expected format (JSON or HTML) when a validation exception occurs.
     *
     * @param  \Illuminate\Http\Request  $request  The current HTTP request instance.
     * @param  ValidationException  $exception  The validation exception instance.
     * @return \Illuminate\Http\Response The generated HTTP response.
     */
    protected function buildValidationResponse($request, ValidationException $exception): Response
    {
        $statusCode = 422;
        $errors = $exception->errors();

        if ($request->wantsJson()) {
            return response()->json(data: [
                'message' => __(key: "jaspur::http-statuslist.{$statusCode}"),
                'errors' => $errors,
                'status' => $statusCode,
            ], status: $statusCode);
        }

        return response(content: view(view: "errors::{$statusCode}", data: [
            'code' => $statusCode,
            'errors' => $errors,
            'exception' => $exception,
        ]), status: $statusCode);
    }
}
