<?php

declare(strict_types=1);

class DB
{
    private static ?DB $instance = null;
    private \PDO $conn;


    private string $host = 'database.cc.localhost';
    private string $user = 'test_user';
    private string $pass = 'test_password';
    private string $dbname = 'course_catalog';

    private function __construct()
    {
        //implementing singleton pattern to ensure only one instance of DB is created
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $this->conn = new \PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Database Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): DB
    {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->conn;
    }
}
