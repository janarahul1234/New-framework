<?php

namespace Core\Http;

class Request
{
    private array $headers = [];
    private array $body = [];

    public function __construct()
    {
        $this->headers = $this->getAllHeaders();
        $this->parseBody();
    }

    public function url(): string
    {
        $rootUrl = env('APP_ENV') === 'production'
        ? dirname($_SERVER['SCRIPT_NAME'])
        : '';

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = '/' . trim(substr($uri, strlen($rootUrl)), '/');

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

    public function isSecure(): bool
    {
        return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function header(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }

    public function body(string $key = null, $default = null): mixed
    {
        if ($key === null) {
            return $this->body;
        }
        return $this->body[$key] ?? $default;
    }

    private function parseBody(): void
    {
        if ($this->isJson() && json_last_error() === JSON_ERROR_NONE) {
            $input = file_get_contents('php://input');
            $json = json_decode($input, true);

            if (is_array($json)) {
                $this->body = $this->sanitize($json);
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

    public function validate(array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $this->body($field);
            $ruleParts = explode('|', $rule);

            foreach ($ruleParts as $rulePart) {
                if ($rulePart === 'required' && empty($value)) {
                    $errors[$field][] = 'The ' . $field . ' field is required.';
                }

                if ($rulePart === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'The ' . $field . ' field must be a valid email address.';
                }

                if (str_starts_with($rulePart, 'min:')) {
                    $min = (int) substr($rulePart, 4);
                    if (strlen($value) < $min) {
                        $errors[$field][] = 'The ' . $field . ' field must be at least ' . $min . ' characters.';
                    }
                }

                if (str_starts_with($rulePart, 'max:')) {
                    $max = (int) substr($rulePart, 4);
                    if (strlen($value) > $max) {
                        $errors[$field][] = 'The ' . $field . ' field must not exceed ' . $max . ' characters.';
                    }
                }

                if ($rulePart === 'numeric' && !is_numeric($value)) {
                    $errors[$field][] = 'The ' . $field . ' field must be a number.';
                }

                if ($rulePart === 'alpha' && !ctype_alpha($value)) {
                    $errors[$field][] = 'The ' . $field . ' field must contain only letters.';
                }

                if ($rulePart === 'alpha_num' && !ctype_alnum($value)) {
                    $errors[$field][] = 'The ' . $field . ' field must contain only letters and numbers.';
                }

                if ($rulePart === 'boolean' && !is_bool(filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
                    $errors[$field][] = 'The ' . $field . ' field must be true or false.';
                }

                if (str_starts_with($rulePart, 'in:')) {
                    $options = explode(',', substr($rulePart, 3));
                    if (!in_array($value, $options)) {
                        $errors[$field][] = 'The ' . $field . ' field must be one of the following: ' . implode(', ', $options) . '.';
                    }
                }
            }
        }

        return $errors;
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
