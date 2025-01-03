<?php

namespace Core\Http;

use PDO;

class Validation
{
    private ?PDO $db;
    private array $errors = [];
    private array $validated = [];
    private array $rulesHandlers = [];

    public function __construct(?PDO $db = null)
    {
        $this->db = $db;
        $this->registerRules();
    }

    public function validate(array $data, array $rules): array
    {
        foreach ($rules as $field => $ruleSet) {
            $skipValidation = false;

            foreach (explode('|', $ruleSet) as $rule) {
                if ($this->applyRule($field, $rule, $data)) {
                    $skipValidation = true;
                    break;
                }
            }

            if (!$skipValidation && !isset($this->errors[$field]) && isset($data[$field])) {
                $this->validated[$field] = $data[$field];
            }
        }

        if ($this->fails()) {
            $this->respondWithErrors();
        }

        return $this->validated;
    }

    private function fails(): bool
    {
        return !empty($this->errors);
    }

    private function applyRule(string $field, string $rule, array $data): bool
    {
        [$ruleName, $params] = $this->parseRule($rule);

        if (!isset($this->rulesHandlers[$ruleName])) {
            throw new \Exception("Validation rule '$ruleName' is not registered.");
        }

        $handler = $this->rulesHandlers[$ruleName];
        return $handler($field, $data[$field] ?? null, $params, $this);
    }

    private function parseRule(string $rule): array
    {
        $parts = explode(':', $rule, 2);
        return [$parts[0], $parts[1] ?? null];
    }

    private function addError(string $field, string $message): void
    {
        $this->errors[$field][] = $message;
    }

    public function registerRule(string $ruleName, callable $handler): void
    {
        $this->rulesHandlers[$ruleName] = $handler;
    }

    private function registerRules(): void
    {
        $this->registerRule('required', fn($field, $value, $params, $validation) =>
            empty($value)
            && $validation->addError($field, "The $field field is required.")
        );

        $this->registerRule('nullable', fn($field, $value, $params, $validation) =>
            !array_key_exists($field, $validation->validated()) && !isset($value)
        );

        $this->registerRule('string', fn($field, $value, $params, $validation) =>
            !is_string($value)
            && $validation->addError($field, "The $field field must be a string.")
        );

        $this->registerRule('numeric', fn($field, $value, $params, $validation) =>
            !is_numeric($value)
            && $validation->addError($field, "The $field field must be a number.")
        );

        $this->registerRule('email', fn($field, $value, $params, $validation) =>
            !filter_var($value, FILTER_VALIDATE_EMAIL)
            && $validation->addError($field, "The $field field must be a valid email address.")
        );

        $this->registerRule('min', fn($field, $value, $params, $validation) =>
            strlen($value) < (int) $params
            && $validation->addError($field, "The $field field must be at least $params characters.")
        );

        $this->registerRule('max', fn($field, $value, $params, $validation) =>
            strlen($value) > (int) $params
            && $validation->addError($field, "The $field field may not be greater than $params characters.")
        );

        $this->registerRule('unique', function ($field, $value, $params, $validation) {
            if (!$validation->db) {
                return;
            }

            [$table, $column, $exception] = array_pad(explode(',', $params), 3, null);
            $column = $column ?? $field;

            $query = "SELECT * FROM $table WHERE $column = :value";
            $queryParams = ['value' => $value];

            if ($exception !== null) {
                $query .= " AND id != :id";
                $queryParams['id'] = $exception;
            }

            $stmt = $validation->db->prepare($query);
            $stmt->execute($queryParams);

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                $validation->addError($field, "The $field field must be unique.");
            }

            return false;
        });

        $this->registerRule('exists', function ($field, $value, $params, $validation) {
            if (!$validation->db) {
                return;
            }

            [$table, $column] = array_pad(explode(',', $params), 2, null);
            $column = $column ?? $field;

            $stmt = $validation->db->prepare("SELECT * FROM $table WHERE $column = :value");
            $stmt->execute(['value' => $value]);

            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $validation->addError($field, "The $field field does not exist.");
            }

            return false;
        });
    }

    private function respondWithErrors(): void
    {
        echo Response::json(['errors' => $this->errors], 400);
        exit(0);
    }
}

// EOF
