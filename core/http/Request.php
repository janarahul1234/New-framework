<?php

namespace Core\Http;

use Core\Database;

class Request
{
    private array $headers = [];
    private array $body = [];

    public function __construct()
    {
        $this->headers = $this->getAllHeaders();
        $this->parseBody();
        $this->parseFiles();
    }

    public function url(): string
    {
        $baseUrl = env('APP_ENV') === 'production' ? dirname($_SERVER['SCRIPT_NAME']) : '';

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = '/' . trim(substr($uri, strlen($baseUrl)), '/');

        return $url ?: '/';
    }

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function isJson(): bool
    {
        return $this->header('Content-Type')
        && strpos($this->header('Content-Type'), 'application/json') !== false;
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }

    public function all(): array
    {
        return $this->body;
    }

    public function input(string $key, $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function validate(array $rules): array
    {
        $validation = new Validation(Database::connect());
        return $validation->validate($this->body, $rules);
    }

    public function hasFile(string $key): bool
    {
        return !empty($this->body[$key]);
    }

    public function file(string $key): ?array
    {
        return $this->hasFile($key) ? $this->body[$key] : null;
    }

    private function parseBody(): void
    {
        $input = file_get_contents('php://input');

        if ($this->isJson()) {
            $decodedJson = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decodedJson)) {
                $this->body = $this->sanitize($decodedJson);
                return;
            }
        }

        if ($this->isGet()) {
            $this->body = $this->sanitize($_GET);
            return;
        }

        if ($this->isPost()) {
            $this->body = $this->sanitize($_POST);
            return;
        }

        $this->body = [];
    }

    private function parseFiles(): void
    {
        foreach ($_FILES as $key => $file) {
            if (is_array($file['name'])) {
                $this->body[$key] = array_map(
                    fn($name, $type, $tmp_name, $error, $size) => new UploadFile($name, $type, $tmp_name, $error, $size),
                    $file['name'],
                    $file['type'],
                    $file['tmp_name'],
                    $file['error'],
                    $file['size']
                );
            } else {
                $this->body[$key] = new UploadFile(
                    $file['name'],
                    $file['type'],
                    $file['tmp_name'],
                    $file['error'],
                    $file['size']
                );
            }
        }
    }

    private function sanitize(array $data): array
    {
        return filter_var_array($data, FILTER_SANITIZE_SPECIAL_CHARS) ?: [];
    }

    private function getAllHeaders(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        // Fallback for servers that do not support getallheaders()
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (str_starts_with($name, 'HTTP_')) {
                $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($name, 5)))));
                $headers[$header] = $value;
            }
        }
        return $headers;
    }
}

// EOF
