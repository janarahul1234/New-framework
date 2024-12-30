<?php

// Define the root directory
define('ROOT_DIR', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../core/functions.php';

use Core\Framework;
use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(ROOT_DIR);
if (!file_exists(ROOT_DIR . '/.env')) {
    throw new \RuntimeException('Environment file not found. Please ensure .env file exists.');
}
$dotenv->load();

// Instantiate and run the application
$app = new Framework();
$app->run();

// EOF
