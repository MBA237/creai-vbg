<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class Pole
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function getAll(): array
    {
        $result = $this->conn->query(
            "SELECT * FROM poles ORDER BY ordre ASC, nom ASC"
        );
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBySlug(string $slug): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM poles WHERE slug = ? LIMIT 1");
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }
}