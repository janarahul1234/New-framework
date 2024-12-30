<?php

return [
    'allowed_origins' => ['*'], 
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PUTCH', 'DELETE', 'OPTIONS'],
    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
    'exposed_headers' => [],
    'max_age' => 3600,
    'allow_credentials' => false,
];
