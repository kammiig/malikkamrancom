<?php

declare(strict_types=1);

define('APP_ROOT', dirname(__DIR__));

require APP_ROOT . '/app/Core/Env.php';
require APP_ROOT . '/app/Core/Database.php';
require APP_ROOT . '/app/Core/Csrf.php';
require APP_ROOT . '/app/Core/Auth.php';
require APP_ROOT . '/app/Core/Upload.php';
require APP_ROOT . '/app/Core/Mailer.php';
require APP_ROOT . '/app/Core/Captcha.php';
require APP_ROOT . '/app/Core/helpers.php';

\App\Core\Env::load(APP_ROOT . '/.env');

$timezone = \App\Core\Env::get('APP_TIMEZONE', 'UTC');
date_default_timezone_set($timezone);

if (session_status() === PHP_SESSION_NONE) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

