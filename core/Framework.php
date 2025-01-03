<?php

namespace Core;

use Core\Http\Request;

class Framework
{
    private Router $router;
    private Request $request;
    private ErrorHandler $errorHandler;

    public function __construct()
    {
        $this->errorHandler = new ErrorHandler();
        $this->handleErrors();

        $this->corsMiddleware();
    
        $this->loadRoutes();
        
        $this->router = new Router();
    }

    private function loadRoute(string $path, string $prefix = ''): void
    {
        Route::group(['prefix' => $prefix], function () use ($path) {
            require_once ROOT_DIR . $path;
        });
    }

    private function loadRoutes(): void
    {
        $this->loadRoute('/routes/web.php');
        $this->loadRoute('/routes/api.php', '/api');
    }

    public function run(): void
    {
        echo $this->router->resolve();
    }

    public function corsMiddleware(): void
    {
        $corsConfig = require ROOT_DIR . '/config/cors.php';

        header('Access-Control-Allow-Origin: ' . implode(', ', $corsConfig['allowed_origins']));
        header('Access-Control-Allow-Methods: ' . implode(', ', $corsConfig['allowed_methods']));
        header('Access-Control-Allow-Headers: ' . implode(', ', $corsConfig['allowed_headers']));

        if (!empty($corsConfig['exposed_headers'])) {
            header('Access-Control-Expose-Headers: ' . implode(', ', $corsConfig['exposed_headers']));
        }

        if ($corsConfig['allow_credentials']) {
            header('Access-Control-Allow-Credentials: true');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            header('Access-Control-Max-Age: ' . $corsConfig['max_age']);
            exit(0);
        }
    }

    public function handleErrors(): void
    {
        set_exception_handler([$this->errorHandler, 'handleException']);
        set_error_handler([$this->errorHandler, 'handleError']);
        register_shutdown_function([$this->errorHandler, 'handleShutdown']);
    }
}

// EOF
