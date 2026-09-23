<?php
declare(strict_types=1);

include_once __DIR__ . '/../environment.php';

class Database
{
    private string $server_name;
    private string $user_name;
    private string $password;
    private string $database_name;
    private mysqli $conn;

    public function __construct()
    {
        $this->getData();

        // Active les exceptions mysqli (bien plus pratique)
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try {
            $this->conn = new mysqli(
                $this->server_name,
                $this->user_name,
                $this->password,
                $this->database_name
            );
            $this->conn->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            die('Erreur de connexion BDD : ' . $e->getMessage());
        }
    }

    protected function getData(): void
    {
        $this->server_name   = getenv('DATABASE_SERVER')   ?: 'localhost';
        $this->user_name     = getenv('DATABASE_USERNAME') ?: 'root';
        $this->password      = getenv('DATABASE_PASSWORD') ?: '';
        $this->database_name = getenv('DATABASE_NAME')     ?: 'test_db';
    }

    public function connect(): mysqli
    {
        return $this->conn;
    }
}