<?php
declare(strict_types=1);

require_once __DIR__ . '/../environment.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Envoi d'e-mails (SMTP via PHPMailer). Réglages dans .env :
 *   MAIL_DRIVER    smtp | mail | log   (défaut : smtp si MAIL_HOST est renseigné, sinon log)
 *   MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION (tls | ssl | vide)
 *   MAIL_FROM_ADDRESS, MAIL_FROM_NAME
 *   MAIL_TEAM_TO   adresse(s) de l'équipe qui reçoit les notifications (séparées par une virgule)
 *
 * L'envoi ne lève jamais d'exception : un mail qui échoue ne doit pas faire échouer un don,
 * une adhésion ou un message. L'échec est journalisé (error_log) et send() renvoie false.
 */
final class Mailer
{
    private static function env(string $key, string $default = ''): string
    {
        $v = getenv($key);
        return $v === false || $v === '' ? $default : (string) $v;
    }

    private static function driver(): string
    {
        $driver = strtolower(self::env('MAIL_DRIVER'));
        if (in_array($driver, ['smtp', 'mail', 'log'], true)) {
            return $driver;
        }
        return self::env('MAIL_HOST') !== '' ? 'smtp' : 'log';
    }

    /** Adresses de l'équipe (MAIL_TEAM_TO, à défaut l'adresse expéditrice). */
    public static function team(): array
    {
        $raw = self::env('MAIL_TEAM_TO', self::env('MAIL_FROM_ADDRESS'));
        $out = [];
        foreach (explode(',', $raw) as $addr) {
            $addr = trim($addr);
            if (filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                $out[] = $addr;
            }
        }
        return $out;
    }

    /**
     * @param string|string[] $to
     * @param string          $html    contenu HTML complet (voir mail_layout())
     * @param string|null     $replyTo adresse à laquelle répondre (ex. la personne qui a écrit)
     */
    public static function send(string|array $to, string $subject, string $html, ?string $replyTo = null): bool
    {
        $recipients = array_values(array_filter(
            (array) $to,
            static fn ($a) => is_string($a) && filter_var($a, FILTER_VALIDATE_EMAIL)
        ));
        if (!$recipients) {
            return false;
        }

        $driver = self::driver();
        if ($driver === 'log') {
            error_log('[mail:log] À ' . implode(', ', $recipients) . ' — ' . $subject);
            return false;
        }

        $from = self::env('MAIL_FROM_ADDRESS');
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            error_log('[mail] MAIL_FROM_ADDRESS manquant ou invalide : mail « ' . $subject . ' » non envoyé.');
            return false;
        }

        try {
            $mail = new PHPMailer(true);
            $mail->CharSet = PHPMailer::CHARSET_UTF8;
            $mail->Timeout = 10;

            if ($driver === 'smtp') {
                $mail->isSMTP();
                $mail->Host     = self::env('MAIL_HOST');
                $mail->Port     = (int) self::env('MAIL_PORT', '587');
                $mail->SMTPAuth = self::env('MAIL_USERNAME') !== '';
                $mail->Username = self::env('MAIL_USERNAME');
                $mail->Password = self::env('MAIL_PASSWORD');
                $enc = strtolower(self::env('MAIL_ENCRYPTION', 'tls'));
                $mail->SMTPSecure = $enc === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS
                    : ($enc === 'tls' ? PHPMailer::ENCRYPTION_STARTTLS : '');
                $mail->SMTPAutoTLS = $enc !== '';
            } else {
                $mail->isMail();
            }

            $mail->setFrom($from, self::env('MAIL_FROM_NAME', 'CREAI-VBG'));
            foreach ($recipients as $addr) {
                $mail->addAddress($addr);
            }
            if ($replyTo !== null && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
                $mail->addReplyTo($replyTo);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody = trim(html_entity_decode(
                strip_tags(preg_replace('#<(br|/p|/h[1-3]|/li|/tr)[^>]*>#i', "\n", $html) ?? $html),
                ENT_QUOTES,
                'UTF-8'
            ));

            $mail->send();
            return true;
        } catch (MailException | Throwable $e) {
            error_log('[mail] Échec « ' . $subject . ' » : ' . $e->getMessage());
            return false;
        }
    }
}
