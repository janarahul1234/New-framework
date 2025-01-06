<?php

function env(string $key, mixed $default = null): mixed
{
    return $_ENV[$key] ?? $default;
}

function route(string $name): string
{
    return env('APP_URL') . Core\Route::findByName($name) ?? '';
}

function asset(string $filename): string
{
    return env('APP_URL') . "/public/{$filename}";
}

function view(string $name, array $values = []): string
{
    return (new Core\View())->render($name, $values);
}

function response(): Core\Http\Response
{
    return new Core\Http\Response();
}

function timestamp(): string
{
    date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));
    return date('Y-m-d H:i:s');
}

function dd(mixed $value): void
{
    echo '<pre>';
    print_r($value);
    echo '</pre>';
    die();
}

// EOF
