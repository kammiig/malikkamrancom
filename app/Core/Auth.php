<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Auth
{
    public static function check(): bool
    {
        return !empty($_SESSION['admin_user_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        $statement = Database::connect()->prepare('SELECT id, name, email FROM users WHERE id = ? LIMIT 1');
        $statement->execute([$_SESSION['admin_user_id']]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public static function attempt(string $email, string $password): bool
    {
        $statement = Database::connect()->prepare('SELECT * FROM users WHERE email = ? AND is_active = 1 LIMIT 1');
        $statement->execute([$email]);
        $user = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION['admin_user_id'] = (int) $user['id'];

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['admin_user_id']);
        session_regenerate_id(true);
    }

    public static function requireAdmin(): void
    {
        if (!self::check()) {
            redirect('/admin/login');
        }
    }
}

