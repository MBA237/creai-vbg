<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

class Migration
{
    private mysqli $conn;
    private string $migrationsDir;

    public function __construct()
    {
        $this->conn = (new Database())->connect();
        $this->migrationsDir = __DIR__ . '/../migrations';

        // Table de suivi
        $this->conn->query("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(255) NOT NULL UNIQUE,
                played_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    private function getPlayed(): array
    {
        $result = $this->conn->query("SELECT filename FROM migrations");
        return array_column($result->fetch_all(MYSQLI_ASSOC), 'filename');
    }

    private function getAvailable(): array
    {
        $files = glob($this->migrationsDir . '/*.sql') ?: [];
        $files = array_map('basename', $files);
        sort($files);
        return $files;
    }

    public function up(): void
    {
        $played    = $this->getPlayed();
        $available = $this->getAvailable();
        $pending   = array_diff($available, $played);

        if (empty($pending)) {
            echo "✅ Aucune migration en attente.\n";
            return;
        }

        foreach ($pending as $file) {
            echo "⏳ Migration : $file ... ";

            $sql = file_get_contents($this->migrationsDir . '/' . $file);

            try {
                $this->conn->begin_transaction();

                // mysqli::multi_query gère plusieurs requêtes séparées par ;
                if (!$this->conn->multi_query($sql)) {
                    throw new Exception($this->conn->error);
                }
                // Vide tous les résultats pendants
                while ($this->conn->more_results() && $this->conn->next_result()) {}

                $stmt = $this->conn->prepare("INSERT INTO migrations (filename) VALUES (?)");
                $stmt->bind_param('s', $file);
                $stmt->execute();
                $stmt->close();

                $this->conn->commit();
                echo "✅ OK\n";
            } catch (Throwable $e) {
                $this->conn->rollback();
                echo "❌ ERREUR : " . $e->getMessage() . "\n";
                exit(1);
            }
        }

        echo "\n🎉 Toutes les migrations ont été jouées.\n";
    }

    public function status(): void
    {
        $played    = $this->getPlayed();
        $available = $this->getAvailable();

        printf("%-60s %s\n", "Fichier", "Statut");
        echo str_repeat("-", 75) . "\n";

        foreach ($available as $file) {
            $status = in_array($file, $played, true) ? "✅ jouée" : "⏳ en attente";
            printf("%-60s %s\n", $file, $status);
        }
    }
}