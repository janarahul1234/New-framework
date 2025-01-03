<?php

namespace Core\Http;

abstract class Controller
{
    public function __call(string $name, array $args)
    {
        $class = 'Core\\Packages\\' . ucfirst($name);
        return class_exists($class) ? new $class() : null;
    }
}

// EOF
