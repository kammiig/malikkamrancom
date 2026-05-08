<?php

declare(strict_types=1);

use App\Core\Csrf;
use App\Core\Database;
use App\Core\Env;

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function nl2p(?string $value): string
{
    $value = trim((string) $value);
    if ($value === '') {
        return '';
    }

    $paragraphs = preg_split("/\R{2,}/", $value) ?: [];

    return implode('', array_map(static fn ($paragraph) => '<p>' . nl2br(e(trim($paragraph))) . '</p>', $paragraphs));
}

function db(): PDO
{
    return Database::connect();
}

function url(string $path = ''): string
{
    $base = rtrim((string) Env::get('APP_URL', ''), '/');
    $path = '/' . ltrim($path, '/');

    return $base . ($path === '/' ? '/' : $path);
}

function asset(string $path): string
{
    return url(ltrim($path, '/'));
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function csrf_field(): string
{
    return Csrf::field();
}

function view(string $view, array $data = [], ?string $layout = 'public/layout'): void
{
    extract($data, EXTR_SKIP);
    ob_start();
    require APP_ROOT . '/app/Views/' . $view . '.php';
    $content = ob_get_clean();

    if ($layout !== null) {
        require APP_ROOT . '/app/Views/' . $layout . '.php';
        return;
    }

    echo $content;
}

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    if (!isset($_SESSION['_flash'][$key])) {
        return null;
    }

    $value = $_SESSION['_flash'][$key];
    unset($_SESSION['_flash'][$key]);

    return $value;
}

function old(string $key, mixed $default = ''): mixed
{
    return $_SESSION['_old'][$key] ?? $default;
}

function remember_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function str_slug(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/i', '-', $value) ?: '';

    return trim($value, '-') ?: bin2hex(random_bytes(4));
}

function setting(string $key, mixed $default = ''): mixed
{
    static $settings = null;

    if ($settings === null) {
        try {
            $settings = [];
            $rows = db()->query('SELECT `key`, `value` FROM settings')->fetchAll();
            foreach ($rows as $row) {
                $settings[$row['key']] = $row['value'];
            }
        } catch (Throwable) {
            $settings = [];
        }
    }

    return $settings[$key] ?? $default;
}

function refresh_settings_cache(): void
{
    setting('__never__');
}

function checked(mixed $actual, mixed $expected = 1): string
{
    return (string) $actual === (string) $expected ? 'checked' : '';
}

function selected(mixed $actual, mixed $expected): string
{
    return (string) $actual === (string) $expected ? 'selected' : '';
}

