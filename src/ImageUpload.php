<?php
declare(strict_types=1);

/**
 * Enregistrement sécurisé d'une image envoyée depuis l'admin.
 *
 * Ne fait JAMAIS confiance au client : ni au type MIME déclaré, ni au nom
 * du fichier. Le type est lu dans le contenu réel du fichier et l'extension
 * est imposée par le serveur (un « shell.php » déguisé en image est refusé).
 */
final class ImageUpload
{
    /** Types acceptés => extension écrite sur le disque. */
    private const TYPES = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];

    private const PHP_ERRORS = [
        UPLOAD_ERR_INI_SIZE   => 'Le fichier dépasse la taille autorisée par le serveur.',
        UPLOAD_ERR_FORM_SIZE  => 'Le fichier est trop lourd.',
        UPLOAD_ERR_PARTIAL    => 'Le fichier n\'a été envoyé que partiellement.',
        UPLOAD_ERR_NO_FILE    => 'Aucun fichier reçu.',
        UPLOAD_ERR_NO_TMP_DIR => 'Dossier temporaire manquant sur le serveur.',
        UPLOAD_ERR_CANT_WRITE => 'Impossible d\'écrire le fichier sur le serveur.',
    ];

    /**
     * @param array  $file    Entrée de $_FILES
     * @param string $absDir  Dossier de destination (chemin disque)
     * @param string $urlDir  Même dossier vu du web, sans slash final (ex. /images/articles)
     * @param string $prefix  Préfixe du nom de fichier (ex. « article »)
     * @return array{url?:string, error?:string}
     */
    public static function store(
        array $file,
        string $absDir,
        string $urlDir,
        string $prefix,
        int $maxBytes = 3 * 1024 * 1024
    ): array {
        $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;
        if ($code !== UPLOAD_ERR_OK) {
            return ['error' => self::PHP_ERRORS[$code] ?? 'Échec de l\'envoi du fichier.'];
        }

        $tmp = (string) ($file['tmp_name'] ?? '');
        if ($tmp === '' || !is_uploaded_file($tmp)) {
            return ['error' => 'Fichier invalide.'];
        }

        if ((int) ($file['size'] ?? 0) > $maxBytes || filesize($tmp) > $maxBytes) {
            return ['error' => 'Image trop lourde (' . (int) round($maxBytes / 1048576) . ' Mo max).'];
        }

        // Type réel lu dans le contenu du fichier
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = (string) $finfo->file($tmp);
        if (!isset(self::TYPES[$mime])) {
            return ['error' => 'Format d\'image non autorisé (JPG, PNG, WebP ou GIF).'];
        }

        // Le fichier doit être une vraie image décodable, du même type
        $info = @getimagesize($tmp);
        if ($info === false || ($info['mime'] ?? '') !== $mime) {
            return ['error' => 'Le fichier n\'est pas une image valide.'];
        }

        if (!is_dir($absDir) && !mkdir($absDir, 0755, true) && !is_dir($absDir)) {
            return ['error' => 'Impossible de créer le dossier de destination.'];
        }

        $name = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . self::TYPES[$mime];
        $dest = rtrim($absDir, '/\\') . DIRECTORY_SEPARATOR . $name;

        if (!move_uploaded_file($tmp, $dest)) {
            return ['error' => 'Échec de l\'enregistrement de l\'image.'];
        }
        @chmod($dest, 0644);

        return ['url' => rtrim($urlDir, '/') . '/' . $name];
    }
}
