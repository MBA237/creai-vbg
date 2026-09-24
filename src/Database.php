<?php
declare(strict_types=1);

// Charge l'environnement UNE SEULE FOIS
include_once __DIR__ . '/../environment.php';

// Protection : ne déclare la classe qu'une seule fois
if (!class_exists('Database')) {

    class Database
    {
        /** Délai maximum (secondes) pour joindre le serveur MySQL. */
        private const CONNECT_TIMEOUT = 5;

        /**
         * Si true, une connexion impossible lève une exception au lieu d'arrêter la page (die).
         * Activé le temps d'un appel à Database::soft().
         */
        private static bool $throwOnFail = false;

        private string $server_name;
        private int $port = 3306;
        private string $user_name;
        private string $password;
        private string $database_name;
        private mysqli $conn;

        public function __construct()
        {
            $this->getData();

            // Active les exceptions mysqli
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            try {
                $conn = mysqli_init();
                // Sans délai, un serveur injoignable bloquerait chaque page pendant ~1 minute
                $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, self::CONNECT_TIMEOUT);
                $conn->real_connect(
                    $this->server_name,
                    $this->user_name,
                    $this->password,
                    $this->database_name,
                    $this->port
                );
                $conn->set_charset('utf8mb4');
                $this->conn = $conn;
            } catch (mysqli_sql_exception $e) {
                self::fail($e);
            }
        }

        /**
         * Connexion impossible : on journalise le détail et on affiche un message sobre.
         * Le détail (qui peut révéler l'utilisateur ou le serveur MySQL) n'est montré que si
         * APP_DEBUG=1 est défini dans le .env — à retirer dès que le problème est résolu.
         */
        private static function fail(mysqli_sql_exception $e): never
        {
            error_log('[Database] Connexion impossible : ' . $e->getMessage());

            if (self::$throwOnFail) {
                throw new RuntimeException('Connexion à la base de données impossible', 0, $e);
            }


            if (!headers_sent()) {
                http_response_code(503);
            }

            $debug = in_array(strtolower((string) getenv('APP_DEBUG')), ['1', 'true', 'on', 'yes'], true);
            if ($debug) {
                die('Erreur de connexion à la base de données : ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
            }

            die('Le site est momentanément indisponible. Merci de réessayer dans quelques instants.');
        }

        protected function getData(): void
        {
            $server = getenv('DATABASE_SERVER') ?: 'localhost';

            // Accepte « serveur:port » (ex. localhost:3306) : mysqli, lui, attend le port à part.
            // Sans cela, « localhost:3306 » serait pris pour un nom d'hôte et la connexion échouerait.
            if (preg_match('/^([^:\[\]]+):(\d{1,5})$/', $server, $m)) {
                $server     = $m[1];
                $this->port = (int) $m[2];
            }

            $this->server_name   = $server;
            $this->user_name     = getenv('DATABASE_USERNAME') ?: 'root';
            $this->password      = getenv('DATABASE_PASSWORD') ?: '';
            $this->database_name = getenv('DATABASE_NAME')     ?: 'creai_vbg';
        }

        public function connect(): mysqli
        {
            return $this->conn;
        }

        /**
         * Exécute $fn (lecture de données) sans jamais interrompre la page : base injoignable,
         * table manquante (migration non lancée)… l'erreur est journalisée et $fallback est renvoyé.
         * try/catch seul ne suffit pas : une connexion impossible appelle die(), qu'on ne peut pas attraper.
         */
        public static function soft(callable $fn, mixed $fallback = null): mixed
        {
            $previous = self::$throwOnFail;
            self::$throwOnFail = true;

            try {
                return $fn();
            } catch (Throwable $e) {
                error_log('[Database::soft] ' . $e->getMessage());
                return $fallback;
            } finally {
                self::$throwOnFail = $previous;
            }
        }
    }
}
