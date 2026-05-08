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

function versioned_asset(string $path): string
{
    $url = asset($path);
    $absolutePath = APP_ROOT . '/public/' . ltrim($path, '/');
    if (!is_file($absolutePath)) {
        return $url;
    }

    return $url . '?v=' . filemtime($absolutePath);
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

function icon_options(): array
{
    return [
        'globe' => 'Globe',
        'monitor' => 'Monitor',
        'code' => 'Code',
        'shopping-bag' => 'Shopping Bag',
        'cart' => 'Shopping Cart',
        'server' => 'Server',
        'cloud' => 'Cloud',
        'dashboard' => 'Dashboard',
        'wrench' => 'Wrench',
        'settings' => 'Settings',
        'wordpress' => 'WordPress / CMS',
        'github' => 'GitHub',
        'linkedin' => 'LinkedIn',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'google' => 'Google',
    ];
}

function social_icon_options(): array
{
    return [
        'github' => 'GitHub',
        'linkedin' => 'LinkedIn',
        'whatsapp' => 'WhatsApp',
        'email' => 'Email',
        'facebook' => 'Facebook',
        'instagram' => 'Instagram',
        'globe' => 'Website',
        'google' => 'Google',
    ];
}

function service_icon_options(): array
{
    return [
        'monitor' => 'Monitor / Business Website',
        'code' => 'Code / WordPress',
        'wordpress' => 'WordPress / CMS',
        'shopping-bag' => 'Shopping Bag / Store',
        'server' => 'Server / Hosting',
        'cloud' => 'Cloud / WHMCS',
        'dashboard' => 'Dashboard / Web App',
        'wrench' => 'Wrench / Maintenance',
        'settings' => 'Settings / Optimisation',
        'globe' => 'Globe',
    ];
}

function icon_svg(?string $name, string $class = 'ui-icon'): string
{
    $key = strtolower(trim((string) $name));
    $key = preg_replace('/[^a-z0-9-]+/', '-', $key) ?: 'globe';
    $aliases = [
        'bw' => 'monitor',
        'wp' => 'code',
        'sp' => 'shopping-bag',
        'wh' => 'server',
        'db' => 'dashboard',
        'mo' => 'wrench',
        'mail' => 'email',
    ];
    $key = $aliases[$key] ?? $key;

    if ($key === 'google') {
        return '<svg class="' . e($class) . '" width="18" height="18" style="width:18px;height:18px;max-width:18px;max-height:18px;display:inline-block;vertical-align:middle;flex:0 0 18px" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="#4285F4" d="M21.6 12.2c0-.7-.1-1.4-.2-2H12v3.8h5.4a4.6 4.6 0 0 1-2 3v2.5h3.2c1.9-1.8 3-4.3 3-7.3Z"/><path fill="#34A853" d="M12 22c2.7 0 5-0.9 6.6-2.5L15.4 17c-.9.6-2 1-3.4 1a6 6 0 0 1-5.7-4.1H3v2.6A10 10 0 0 0 12 22Z"/><path fill="#FBBC05" d="M6.3 13.9a6 6 0 0 1 0-3.8V7.5H3a10 10 0 0 0 0 9l3.3-2.6Z"/><path fill="#EA4335" d="M12 6c1.5 0 2.8.5 3.9 1.5l2.8-2.8A9.5 9.5 0 0 0 12 2a10 10 0 0 0-9 5.5l3.3 2.6A6 6 0 0 1 12 6Z"/></svg>';
    }

    $icons = [
        'globe' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.3 2.5 3.5 5.5 3.5 9S14.3 18.5 12 21M12 3c-2.3 2.5-3.5 5.5-3.5 9S9.7 18.5 12 21"/>',
        'monitor' => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/>',
        'code' => '<path d="m9 18-6-6 6-6M15 6l6 6-6 6"/>',
        'wordpress' => '<circle cx="12" cy="12" r="9"/><path d="M6.8 8h2.4l3.1 8.4L14 11.8 12.7 8h2.1l3 8.4M8.2 8h7.6"/>',
        'shopping-bag' => '<path d="M6 8h12l-1 12H7L6 8Z"/><path d="M9 8a3 3 0 0 1 6 0"/>',
        'cart' => '<circle cx="9" cy="20" r="1.5"/><circle cx="17" cy="20" r="1.5"/><path d="M3 4h2l2.2 11h10.6L20 7H7"/>',
        'server' => '<rect x="4" y="4" width="16" height="6" rx="2"/><rect x="4" y="14" width="16" height="6" rx="2"/><path d="M8 7h.01M8 17h.01"/>',
        'cloud' => '<path d="M17.5 18H8a5 5 0 1 1 1.1-9.9A6 6 0 0 1 20 11.5 3.5 3.5 0 0 1 17.5 18Z"/>',
        'dashboard' => '<rect x="3" y="3" width="8" height="8" rx="2"/><rect x="13" y="3" width="8" height="5" rx="2"/><rect x="13" y="10" width="8" height="11" rx="2"/><rect x="3" y="13" width="8" height="8" rx="2"/>',
        'wrench' => '<path d="M14.7 6.3a4 4 0 0 0 5 5L11 20a2.1 2.1 0 0 1-3-3l8.7-8.7a4 4 0 0 0-2-2Z"/>',
        'settings' => '<path d="M12 15.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2 3.4-.2-.1a1.8 1.8 0 0 0-2.1.2 1.7 1.7 0 0 0-.8 1.6V22H10v-.2a1.8 1.8 0 0 0-1.1-1.6 1.8 1.8 0 0 0-2 .1l-.2.1-2-3.4.1-.1A1.7 1.7 0 0 0 5.1 15 1.8 1.8 0 0 0 3.7 14H3v-4h.7a1.8 1.8 0 0 0 1.4-1 1.7 1.7 0 0 0-.3-1.9l-.1-.1 2-3.4.2.1a1.8 1.8 0 0 0 2.1-.2A1.7 1.7 0 0 0 10 2h4.7v.1a1.8 1.8 0 0 0 1.1 1.6 1.8 1.8 0 0 0 2-.1l.2-.1 2 3.4-.1.1a1.7 1.7 0 0 0-.3 1.9 1.8 1.8 0 0 0 1.4 1h.7v4H21a1.8 1.8 0 0 0-1.6 1Z"/>',
        'github' => '<path d="M12 2a10 10 0 0 0-3.2 19c.5.1.7-.2.7-.5v-1.8c-2.9.6-3.5-1.2-3.5-1.2-.5-1.1-1.1-1.4-1.1-1.4-.9-.6.1-.6.1-.6 1 0 1.6 1.1 1.6 1.1.9 1.5 2.4 1.1 3 .8.1-.7.4-1.1.7-1.4-2.3-.3-4.7-1.2-4.7-5.1 0-1.1.4-2.1 1.1-2.8-.1-.3-.5-1.4.1-2.8 0 0 .9-.3 2.9 1.1A10 10 0 0 1 12 5.1c.9 0 1.7.1 2.5.3 2-1.4 2.9-1.1 2.9-1.1.6 1.4.2 2.5.1 2.8.7.7 1.1 1.7 1.1 2.8 0 4-2.4 4.8-4.7 5.1.4.3.8 1 .8 2v3.5c0 .3.2.6.8.5A10 10 0 0 0 12 2Z"/>',
        'linkedin' => '<path d="M6.5 9.5V20M6.5 5.5v.01M11 20v-6.2c0-2.9 4.5-3.2 4.5.1V20M11 10h4.5"/>',
        'whatsapp' => '<path d="M4 20l1.3-4A8 8 0 1 1 8 18.7L4 20Z"/><path d="M9 8.8c.4 3.2 2 4.8 5.2 5.2l1-1.5-1.8-1-1 1c-1-.5-1.6-1.1-2-2l1-1L10.5 7 9 8.8Z"/>',
        'email' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m4 7 8 6 8-6"/>',
        'facebook' => '<path d="M14 8h3V4h-3a5 5 0 0 0-5 5v3H6v4h3v6h4v-6h3l1-4h-4V9a1 1 0 0 1 1-1Z"/>',
        'instagram' => '<rect x="4" y="4" width="16" height="16" rx="5"/><circle cx="12" cy="12" r="3.5"/><path d="M17.5 6.5h.01"/>',
    ];

    $body = $icons[$key] ?? $icons['globe'];

    return '<svg class="' . e($class) . '" viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">' . $body . '</svg>';
}
