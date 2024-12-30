<?php

namespace Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(ROOT_DIR . '/app/views');
        $this->twig = new Environment($loader, [
            'cache' => ROOT_DIR . '/storage/cache/twig',
            'debug' => env('APP_DEBUG', false),
        ]);

        if (env('APP_DEBUG', false)) {
            $this->twig->addExtension(new \Twig\Extension\DebugExtension());
        }
    }

    public function render(string $template, array $data = []): string
    {
        return $this->twig->render("{$template}.twig", $data);
    }
}

// EOF
