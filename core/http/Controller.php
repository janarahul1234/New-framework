<?php

namespace Core\Http;

abstract class Controller
{
    public function use(string $package): ?object
    {
        $class = 'Core\\Packages\\' . ucfirst($package);
        if (class_exists($class)) {
            return new $class();
        }
        
        return null;
    }
}

// EOF
