<?php

namespace Core\Http;

class Response
{
    private static int $statusCode = 200;

    public static function status(int $code): self
    {
        self::$statusCode = $code;
        http_response_code($code);
        return new static;
    }

    public static function redirect(string $path, int $code = 301): void
    {
        header("Location: {$path}", true, $code);
        exit(0);
    }

    public static function json(mixed $data, ?int $code = null): string
    {
        $statusCode = $code ?? self::$statusCode;
        self::$statusCode = $statusCode;
        header('Content-Type: application/json', true, $statusCode);
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public static function abort(int $code = 404): void
    {
        self::status($code);
        if ($code === 404) {
            echo '404 | Not Found';
        } elseif ($code === 500) {
            echo '500 | Internal Server Error';
        } else {
            echo "{$code} | Error";
        }
        exit(0);
    }
}

// EOF
