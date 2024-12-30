<?php

namespace Core\Packages;

class Cookies
{
    private const DEFAULT_EXPIRY = 0;

    public function set(string $name, string $value, string $expires = ''): bool
    {
        $expiryTime = $this->expiresIn($expires);
        return setcookie($name, $value, $expiryTime, '/', '', false, false);
    }

    public function get(string $name): ?string
    {
        return $this->cookieExists($name) ? $_COOKIE[$name] : null;
    }

    public function delete(string $name): bool
    {
        if ($this->cookieExists($name)) {
            unset($_COOKIE[$name]);
            return setcookie($name, '', time() - 3600, '/');
        }

        return false;
    }

    private function cookieExists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    private function expiresIn(string $expires): int
    {
        if (empty($expires)) {
            return self::DEFAULT_EXPIRY;
        }

        $multipliers = [
            's' => 1,
            'm' => 60,
            'h' => 3600,
            'd' => 86400,
        ];

        $unit = strtolower(substr($expires, -1));
        $number = (int) substr($expires, 0, -1);

        if (isset($multipliers[$unit])) {
            return time() + ($number * $multipliers[$unit]);
        } else {
            throw new \Exception('Invalid time prefix');
        }
    }
}

// EOF
