<?php

namespace App\Database;

class Database
{
    private static $instance = null;
    private \PDO $connection;

    private function __construct()
    {
        $config = Config::get();
        $this->connection = new \PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'], $config['user'], $config['password']);
        $this->connection->setAttribute(\PDO::ATTR_CASE, \PDO::CASE_NATURAL);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}
