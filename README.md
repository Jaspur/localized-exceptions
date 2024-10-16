# Jaspur Localized Exceptions ✨

A custom exception handler package for Laravel that provides localized error messages for HTTP exceptions.

## Requirements 📋

To use this package, you need:

-   **PHP**: ^8.2
-   **Laravel**: ^11.0

Ensure you have the correct versions installed to avoid compatibility issues.

## Installation 📦

To install the package, use Composer:

```bash
composer require jaspur/localized-exceptions
```

## Service Provider ⚙️

After installation, the service provider will automatically be registered. If you need to manually register it, add the following line to the `providers` array in your `config/app.php`:

```php
Jaspur\LocalizedExceptions\LocalizedExceptionsServiceProvider::class,
```

## How It Works 🛠️

This package overrides Laravel's default exception handling to provide localized messages for various HTTP exceptions. It does this by mapping common HTTP exceptions to their respective status codes and returning appropriate localized messages.

### Exception Mapping

The package maps the following exceptions to their respective HTTP status codes:

-   **401**: Authentication Failed
-   **403**: Authorization Denied
-   **404**: Resource Not Found
-   **405**: Method Not Allowed
-   **406**: Not Acceptable
-   **409**: Conflict
-   **410**: Gone
-   **411**: Length Required
-   **412**: Precondition Failed
-   **428**: Precondition Required
-   **422**: Unprocessable Entity
-   **423**: Locked
-   **429**: Too Many Requests
-   **503**: Service Unavailable
-   **415**: Unsupported Media Type

When an exception occurs, the package checks if it matches one of the mapped exceptions and returns a localized message accordingly.

### Validation Errors 📝

The package also handles validation exceptions, returning a list of validation errors along with a localized message.

## Localization 🌍

You can customize the error messages by creating language files in the `resources/lang/vendor/jaspur` directory. The package loads translations from the `resources/lang` directory.

## Publishing Translations 📢

If you want to customize the translation files, you can publish them using:

```bash
php artisan vendor:publish --tag=lang
```

This will copy the language files to `resources/lang/vendor/jaspur`.

## Contributing 🤝

Contributions are welcome! Please feel free to submit a pull request or open an issue for any bugs or feature requests.

## License 📝

This package is licensed under the MIT License.
