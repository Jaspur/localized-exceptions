<?php

namespace Jaspur\LocalizedExceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;

class LocalizedExceptionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstraps the application services.
     *
     * This method loads the translation files from the specified directory
     * and publishes them to the application's resource path for language files.
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'jaspur');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/jaspur'),
        ], 'lang');
    }

    /**
     * Register the application's exception handler.
     *
     * This method binds the ExceptionHandler interface to the
     * LocalizedExceptionHandler implementation as a singleton in the
     * application's service container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ExceptionHandler::class, LocalizedExceptionHandler::class);
    }
}
