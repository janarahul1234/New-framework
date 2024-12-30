<?php

namespace Core;

class Route
{
    private static int $index;
    private static array $routes = [];

    public static function get(string $path, array | callable $action): self
    {
        return self::add('GET', $path, $action);
    }

    public static function post(string $path, array | callable $action): self
    {
        return self::add('POST', $path, $action);
    }

    public static function put(string $path, array | callable $action): self
    {
        return self::add('PUT', $path, $action);
    }

    public static function delete(string $path, array | callable $action): self
    {
        return self::add('DELETE', $path, $action);
    }

    public static function patch(string $path, array | callable $action): self
    {
        return self::add('PATCH', $path, $action);
    }

    public static function options(string $path, array | callable $action): self
    {
        return self::add('OPTIONS', $path, $action);
    }

    public static function fallback(string $view): void
    {
        self::$routes['fallback'] = $view;
    }

    private static function add(string $method, string $path, array | callable $action): self
    {
        self::$routes[] = [
            'path' => $path,
            'method' => $method,
            'action' => $action,
            'name' => null,
            'middleware' => [],
        ];

        self::$index = array_key_last(self::$routes);
        return new static;
    }

    public static function name(string $name): self
    {
        self::$routes[self::$index]['name'] = $name;
        return new static;
    }

    public static function middleware(array | callable $middleware): self
    {
        self::$routes[self::$index]['middleware'] = $middleware;
        return new static;
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }

    public static function findByName(string $name): ?string
    {
        foreach (self::$routes as $route) {
            if ($route['name'] === $name) {
                return $route['path'];
            }
        }
        return null;
    }
}
