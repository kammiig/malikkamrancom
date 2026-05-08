<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    exit("This script can only be run from the command line.\n");
}

require dirname(__DIR__) . '/config/bootstrap.php';

$name = $argv[1] ?? 'Admin';
$email = $argv[2] ?? '';
$password = $argv[3] ?? '';

if ($email === '' || $password === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 10) {
    exit("Usage: php tools/create_admin.php \"Admin Name\" admin@example.com \"StrongPassword123\"\nPassword must be at least 10 characters.\n");
}

$hash = password_hash($password, PASSWORD_DEFAULT);
$statement = db()->prepare(
    'INSERT INTO users (name, email, password_hash, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())
     ON DUPLICATE KEY UPDATE name = VALUES(name), password_hash = VALUES(password_hash), is_active = 1, updated_at = NOW()'
);
$statement->execute([$name, $email, $hash]);

echo "Admin user created or updated for {$email}.\n";

