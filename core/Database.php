<?php

namespace Core;

use PDO;

class Database
{
    private static $connection;

    public static function connect()
    {
        if (!self::$connection) {
            $config = require_once ROOT_DIR . '/config/database.php';
            $url = "mysql:host={$config['host']};dbname={$config['database']};port={$config['port']};charset=utf8mb4";

            try {
                self::$connection = new PDO($url, $config['username'], $config['password'], [
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            } catch (\PDOException $e) {
                throw new \Exception("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}

// EOF
