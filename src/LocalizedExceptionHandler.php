<?php

namespace Jaspur\LocalizedExceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as BaseHandler;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class LocalizedExceptionHandler extends BaseHandler
{
    /**
     * Renders the given exception into an HTTP response.
     * Instead of using the original message, it transforms it to a localized message
     *
     * @param  mixed  $request  The incoming request instance.
     * @param  Throwable  $exception  The exception that needs to be rendered.
     * @return Response The rendered HTTP response.
     */
    public function render($request, Throwable $exception): Response
    {
        return parent::render($request, new Exception(message: __($exception->getMessage()), code: $exception->getCode(), previous: $exception));
    }
}
