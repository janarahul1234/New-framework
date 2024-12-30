<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;

class ErrorHandler
{
    private Request $request;
    private string $environment;
    private bool $displayErrors;
    private string $logPath;

    public function __construct()
    {
        $this->request = new Request();
        $this->environment = env('APP_ENV', 'production');
        $this->displayErrors = $this->environment === 'development';
        $this->logPath = ROOT_DIR . '/storage/logs/error.log';

        if (!is_dir(dirname($this->logPath))) {
            mkdir(dirname($this->logPath), 0755, true);
        }
    }

    public function handleException($exception): void
    {
        $this->logError($exception);

        $responseFormat = $this->determineResponseFormat();
        $errorDetails = $this->formatError($exception, true);

        if ($responseFormat === 'json') {
            echo Response::json($errorDetails, 500);
        } else {
            Response::status(500);
            echo $this->renderErrorPage($errorDetails);
        }
    }

    public function handleError($errno, $errstr, $errfile, $errline): void
    {
        $error = [
            'type' => $this->getErrorType($errno),
            'message' => $errstr,
            'file' => $errfile,
            'line' => $errline,
        ];

        $this->logError($error, false);

        $responseFormat = $this->determineResponseFormat();
        $errorDetails = $this->formatError($error, false);

        if ($responseFormat === 'json') {
            Response::status(500);
            header('Content-Type: application/json');
            echo json_encode($errorDetails);
        } else {
            Response::status(500);
            echo $this->renderErrorPage($errorDetails);
        }
    }

    public function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null) {
            $this->handleError($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }

    private function determineResponseFormat(): string
    {
        $acceptHeader = $this->request->header('Accept') ?? '';
        if (strpos($acceptHeader, 'application/json') !== false) {
            return 'json';
        }
        return 'html';
    }

    private function formatError($error, bool $isException): array
    {
        if ($this->environment === 'development') {
            if ($isException) {
                return [
                    'type' => get_class($error),
                    'message' => $error->getMessage(),
                    'file' => $error->getFile(),
                    'line' => $error->getLine(),
                    'stack' => $error->getTrace(),
                ];
            } else {
                return $error;
            }
        }

        return [
            'type' => $isException ? get_class($error) : $error['type'],
            'message' => $isException ? 'An unexpected error occurred.' : 'An error occurred.',
        ];
    }

    private function renderErrorPage(array $errorDetails): string
    {
        $type = htmlspecialchars($errorDetails['type'], ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($errorDetails['message'], ENT_QUOTES, 'UTF-8');

        if ($this->environment === 'development') {
            $file = htmlspecialchars($errorDetails['file'] ?? '', ENT_QUOTES, 'UTF-8');
            $line = htmlspecialchars($errorDetails['line'] ?? '', ENT_QUOTES, 'UTF-8');
            $stack = htmlspecialchars(print_r($errorDetails['stack'] ?? '', true), ENT_QUOTES, 'UTF-8');

            return <<<HTML
                <h1>{$type}</h1>
                <p><strong>Message:</strong> {$message}</p>
                <p><strong>File:</strong> {$file}</p>
                <p><strong>Line:</strong> {$line}</p>
                <pre><strong>Stack Trace:</strong>\n{$stack}</pre>
            HTML;
        }

        return <<<HTML
            <h1>{$type}</h1>
            <p>{$message}</p>
        HTML;
    }

    private function getErrorType(int $errno): string
    {
        return match ($errno) {
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Runtime Notice',
            E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
            default => 'Unknown Error',
        };
    }

    private function logError($error, bool $isException = true): void
    {
        $logMessage = '[' . timestamp() . '] ';
        if ($isException) {
            /** @var \Exception $error */
            $logMessage .= get_class($error) . ': ' . $error->getMessage() . ' in ' .
            $error->getFile() . ' on line ' . $error->getLine() . PHP_EOL;
            $logMessage .= $error->getTraceAsString() . PHP_EOL;
        } else {
            $logMessage .= "{$error['type']}: {$error['message']} in {$error['file']} on line {$error['line']}" . PHP_EOL;
        }

        file_put_contents($this->logPath, $logMessage, FILE_APPEND);
    }
}

// EOF
