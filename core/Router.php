<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;

class Router
{
    private Request $request;
    private array $routes = [];
    private static ?array $memoryCache = null;

    public function __construct()
    {
        $this->request = new Request();
        $this->routes = Route::getRoutes();

        $cacheType = env('CACHE_TYPE', 'file');

        if ($cacheType === 'memory') {
            if (self::$memoryCache === null) {
                self::$memoryCache = $this->routes;
            } else {
                $this->routes = self::$memoryCache;
            }
        } elseif ($cacheType === 'file') {
            $cacheFile = ROOT_DIR . '/storage/cache/routes.cache';

            if (file_exists($cacheFile)) {
                $this->routes = unserialize(file_get_contents($cacheFile));
            } else {
                $this->saveCacheRoutesToFile($cacheFile);
            }
        }
    }

    private function saveCacheRoutesToFile(string $cacheFile): void
    {
        $cacheDir = dirname($cacheFile);
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        file_put_contents($cacheFile, serialize($this->routes));
    }

    private function matchRoutes(string $url): ?array
    {
        $matchingRoutes = [];
        foreach ($this->routes as &$route) {
            $route['params'] = [];
            $pattern = preg_replace('/{(\w+)}/', '(\w+)', $route['path']);
            $pattern = str_replace('/', '\/', $pattern);

            if (preg_match('/^' . $pattern . '$/', $url, $values)) {
                array_shift($values);
                if (preg_match_all('/{(\w+)}/', $route['path'], $matches)) {
                    $route['params'] = array_combine($matches[1], $values);
                }
                $matchingRoutes[] = $route;
            }
        }

        return $matchingRoutes;
    }

    public function resolve(): string
    {
        $url = $this->request->url();
        $method = $this->request->method();
        $routes = $this->matchRoutes($url);

        if (!$routes) {
            return $this->abort(404);
        }

        foreach ($routes as $route) {
            if (strtoupper($route['method']) === $method) {
                if ($middlewareResult = $this->executeMiddleware($route['middleware'])) {
                    return $middlewareResult;
                }
                return $this->executeAction($route['action'], $route['params']);
            }
        }

        return $this->abort(405);
    }

    public function abort(int $code = 404): string
    {
        Response::status($code);

        if ($code === 405) {
            return '405 | Method Not Allowed';
        }

        if (isset($this->routes['fallback'])) {
            return view($this->routes['fallback']);
        }

        return '404 | Not Found';
    }

    private function executeMiddleware(array | callable $middleware): ?string
    {
        foreach ((array) $middleware as $handler) {
            if (is_callable($handler)) {
                $result = call_user_func($handler, $this->request);
            } elseif (is_string($handler) && class_exists($handler)) {
                $middlewareInstance = new $handler();
                if (method_exists($middlewareInstance, 'handle')) {
                    $result = $middlewareInstance->handle($this->request);
                } else {
                    $result = null;
                }
            } else {
                $result = null;
            }

            if ($result !== null) {
                return $result;
            }
        }

        return null;
    }

    private function executeAction(array | callable $action, array $params = []): string
    {
        if (is_callable($action)) {
            $result = call_user_func($action, $this->request, ...array_values($params));
            return $this->actionResult($result);
        }

        if (is_array($action) && count($action) === 2) {
            [$controller, $method] = $action;
            if (class_exists($controller) && method_exists($controller, $method)) {
                $controllerInstance = new $controller();
                $result = call_user_func([$controllerInstance, $method], $this->request, ...array_values($params));
                return $this->actionResult($result);
            }
        }

        throw new \Exception("Invalid Controller or method");
    }

    private function actionResult(mixed $result): string
    {
        if (is_string($result)) {
            return $result;
        }

        if (is_array($result) || is_object($result)) {
            return Response::json($result);
        }

        return '';
    }
}

// EOF
