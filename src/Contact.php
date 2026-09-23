<?php
declare(strict_types=1);

if (!class_exists('Database')) {
    require_once __DIR__ . '/Database.php';
}

if (!class_exists('Contact')) {

    class Contact
    {
        private mysqli $conn;

        public function __construct()
        {
            $this->conn = (new Database())->connect();
        }

        public function create(
            string $nom,
            string $email,
            string $sujet,
            string $message,
            ?string $ip = null
        ): bool {
            $stmt = $this->conn->prepare(
                "INSERT INTO contacts (nom, email, sujet, message, ip) VALUES (?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('sssss', $nom, $email, $sujet, $message, $ip);
            $ok = $stmt->execute();
            $stmt->close();
            return $ok;
        }

        public function getAll(): array
        {
            $result = $this->conn->query(
                "SELECT * FROM contacts ORDER BY created_at DESC"
            );
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function countUnread(): int
        {
            $result = $this->conn->query("SELECT COUNT(*) AS n FROM contacts WHERE lu = 0");
            $row = $result->fetch_assoc();
            return (int) ($row['n'] ?? 0);
        }
    }
}