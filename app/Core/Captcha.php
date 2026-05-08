<?php

declare(strict_types=1);

namespace App\Core;

final class Captcha
{
    public static function question(): string
    {
        if (empty($_SESSION['captcha_answer'])) {
            self::refresh();
        }

        return $_SESSION['captcha_question'];
    }

    public static function verify(string $answer): bool
    {
        $valid = isset($_SESSION['captcha_answer']) && trim($answer) === (string) $_SESSION['captcha_answer'];
        self::refresh();

        return $valid;
    }

    public static function refresh(): void
    {
        $a = random_int(2, 9);
        $b = random_int(2, 9);
        $_SESSION['captcha_question'] = $a . ' + ' . $b . ' = ?';
        $_SESSION['captcha_answer'] = $a + $b;
    }
}

