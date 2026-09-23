<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class Benevole
{
    public const CANAUX = [
        'whatsapp'  => 'WhatsApp',
        'mail'      => 'Mail',
        'les_deux'  => 'Les deux',
    ];

    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    public function create(
        string $email,
        string $nom,
        string $prenom,
        string $telephone,
        string $dateNaissance,
        string $ville,
        string $connuPar,
        string $motivations,
        string $experience,
        string $canal,
        string $notes
    ): bool {
        $stmt = $this->conn->prepare(
            "INSERT INTO benevoles
                (email, nom, prenom, telephone, date_naissance, ville, connu_par, motivations, experience, canal, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param(
            'sssssssssss',
            $email,
            $nom,
            $prenom,
            $telephone,
            $dateNaissance,
            $ville,
            $connuPar,
            $motivations,
            $experience,
            $canal,
            $notes
        );
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function getAll(): array
    {
        $result = $this->conn->query("SELECT * FROM benevoles ORDER BY created_at DESC");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function countUnread(): int
    {
        $result = $this->conn->query("SELECT COUNT(*) AS n FROM benevoles WHERE lu = 0");
        $row = $result->fetch_assoc();
        return (int) ($row['n'] ?? 0);
    }

    public function markAsRead(int $id): bool
    {
        $stmt = $this->conn->prepare("UPDATE benevoles SET lu = 1 WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM benevoles WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}
