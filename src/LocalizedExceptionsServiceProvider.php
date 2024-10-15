<?php

namespace Jaspur\LocalizedExceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class LocalizedExceptionsServiceProvider extends ServiceProvider
{
    /**
     * Register the application's exception handler.
     *
     * This method binds the LocalizedExceptionHandler class as a singleton
     * to handle exceptions in the application.
     */
    public function register()
    {
        $this->app->singleton(ExceptionHandler::class, LocalizedExceptionHandler::class);
    }
}
