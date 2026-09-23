<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class Membre
{
    private mysqli $conn;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
    }

    /** Tous les membres avec le nom du pôle */
    public function getAll(): array
    {
        $sql = "SELECT m.*, p.nom AS pole_nom, p.slug AS pole_slug
                FROM membres m
                JOIN poles p ON p.id = m.pole_id
                ORDER BY p.ordre ASC, m.ordre ASC, m.nom ASC";
        return $this->conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    /** Membres d'un pôle donné (par slug) */
    public function getByPoleSlug(string $slug): array
    {
        $sql = "SELECT m.*, p.nom AS pole_nom, p.slug AS pole_slug
                FROM membres m
                JOIN poles p ON p.id = m.pole_id
                WHERE p.slug = ?
                ORDER BY m.ordre ASC, m.nom ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $slug);
        $stmt->execute();
        $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $rows;
    }

    /** Membres groupés par slug de pôle */
    public function getAllGroupedByPole(): array
    {
        $all = $this->getAll();
        $grouped = [];
        foreach ($all as $m) {
            $grouped[$m['pole_slug']][] = $m;
        }
        return $grouped;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM membres WHERE id = ? LIMIT 1");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

    public function create(int $poleId, string $nom, string $profession, string $poste, ?string $photo, int $ordre = 0): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO membres (pole_id, nom, profession, poste, photo, ordre) VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param('issssi', $poleId, $nom, $profession, $poste, $photo, $ordre);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function update(int $id, int $poleId, string $nom, string $profession, string $poste, ?string $photo, int $ordre): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE membres SET pole_id = ?, nom = ?, profession = ?, poste = ?, photo = ?, ordre = ? WHERE id = ?"
        );
        $stmt->bind_param('issssii', $poleId, $nom, $profession, $poste, $photo, $ordre, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM membres WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }
}