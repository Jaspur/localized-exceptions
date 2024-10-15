# Localized Exceptions for Laravel

[![Latest Stable Version](https://poser.pugx.org/jaspur/localized-exceptions/v/stable)](https://packagist.org/packages/jaspur/localized-exceptions)
[![License](https://poser.pugx.org/jaspur/localized-exceptions/license)](https://packagist.org/packages/jaspur/localized-exceptions)

## 🌍 Introduction

**Localized Exceptions** is a Laravel package that automatically localizes exception messages using Laravel's `__()` translation helper. This package is helpful for ensuring exception messages are properly translated according to the application's current locale.

For example, when an exception is thrown, its message will be passed through the translation system, allowing you to display the message in the user's preferred language.

---

## 🚀 Features

-   Translates exception messages using the `__()` helper from Laravel.
-   Seamless integration with Laravel's exception handling system.
-   Compatible with Laravel 11.

---

## 🛠️ Requirements

-   **PHP**: 8.2 or higher
-   **Laravel**: 11

---

## 📦 Installation

To install the package, use Composer:

```bash
composer require jaspur/localized-exceptions
```

---

## 📝 Usage

### Automatic Service Provider Discovery

In **Laravel 11**, service providers are automatically discovered, so you don't need to manually register the service provider.

However, if package auto-discovery is disabled, manually register the service provider in `config/app.php`:

```php
'providers' => [
    Jaspur\LocalizedExceptions\LocalizedExceptionsServiceProvider::class,
],
```

### 💁🏼 How It Works

The package extends Laravel's default exception handler to modify exception messages. It wraps the original exception message in Laravel's `__()` function, allowing the message to be localized based on your application's current language.

---

## 🔧 Example

After installing the package, exceptions thrown in your Laravel app will automatically be passed through the `__()` function.

```php
throw new \Exception('Test exception');
```

If the translation for `'Test exception'` exists in your language files, it will be translated into the appropriate language. If not, the original message will be displayed.

#### How Exceptions Are Handled

The `LocalizedExceptionHandler` class overrides the `render` method like so:

```php
public function render($request, Throwable $exception): Response
{
    return parent::render($request, new Exception(
        message: __($exception->getMessage()),
        code: $exception->getCode(),
        previous: $exception
    ));
}
```

---

## 🧪 Testing

You can test the package by throwing an exception in your Laravel app, like this:

```php
throw new \Exception('Test exception');
```

If translations exist (e.g., in `resources/lang/nl.json`), the message will be localized based on the current locale.

---

## 💡 Contributing

Contributions are welcome! Please feel free to submit a pull request or open an issue on GitHub if you have suggestions or run into any issues.

---

## 📜 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

```

```
