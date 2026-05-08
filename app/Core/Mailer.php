<?php

declare(strict_types=1);

namespace App\Core;

final class Mailer
{
    public static function send(string $to, string $subject, string $message, ?string $replyTo = null): bool
    {
        $from = Env::get('MAIL_FROM_ADDRESS', 'no-reply@localhost');
        $fromName = Env::get('MAIL_FROM_NAME', Env::get('APP_NAME', 'Portfolio Website'));

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . self::sanitizeHeader((string) $fromName) . ' <' . self::sanitizeHeader((string) $from) . '>',
        ];

        if ($replyTo !== null && filter_var($replyTo, FILTER_VALIDATE_EMAIL)) {
            $headers[] = 'Reply-To: ' . self::sanitizeHeader($replyTo);
        }

        return mail($to, $subject, $message, implode("\r\n", $headers));
    }

    private static function sanitizeHeader(string $value): string
    {
        return trim(str_replace(["\r", "\n"], '', $value));
    }
}

