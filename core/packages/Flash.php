<?php

namespace Core\Packages;

class Flash
{
    const FLASH_KEY = 'flash_messages';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION[self::FLASH_KEY])) {
            $_SESSION[self::FLASH_KEY] = [];
        }
    }

    public function set(string $key, string $message): void
    {
        $_SESSION[self::FLASH_KEY][$key] = $message;
    }

    public function get(string $key): ?string
    {
        if (isset($_SESSION[self::FLASH_KEY][$key])) {
            $message = $_SESSION[self::FLASH_KEY][$key];
            unset($_SESSION[self::FLASH_KEY][$key]);
            return $message;
        }
        return null;
    }

    public function has(string $key): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$key]);
    }

    public function all(): array
    {
        $messages = $_SESSION[self::FLASH_KEY] ?? [];
        $_SESSION[self::FLASH_KEY] = [];
        return $messages;
    }
}

// EOF
