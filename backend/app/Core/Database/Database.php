<?php

namespace App\Core\Database;

use PDO;
use PDOException;
use App\Core\Errors\Errors;

/**
 * Database class responsible for handling all database operations
 */
class Database
{
    protected static ?PDO $pdo = null;

    public function __construct()
    {
        if (self::$pdo === null) {
            self::$pdo = $this->connect();
        }
    }

    private function connect(): PDO
    {
        $hostname = DB_HOST;
        $port = DB_PORT;
        $db_name = DB_NAME;
        $username = DB_USER;
        $password = DB_PASS;

        try {
            $dsn = "mysql:host=$hostname;port=$port;dbname=$db_name;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true
            ];
            return new PDO($dsn, $username, $password, $options);
        } catch (PDOException $message) {
            Errors::E500($_REQUEST, $message->getMessage());
            die('Database Connection Error: ' . $message->getMessage());
        }
    }

    public static function getInstance(): PDO
    {
        if (self::$pdo === null) {
            new self();
        }
        return self::$pdo;
    }
}
