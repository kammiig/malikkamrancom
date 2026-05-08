<?php

declare(strict_types=1);

require dirname(__DIR__) . '/config/bootstrap.php';
require APP_ROOT . '/app/Controllers/PublicController.php';
require APP_ROOT . '/app/Controllers/AdminController.php';

use App\Controllers\AdminController;
use App\Controllers\PublicController;
use App\Core\Auth;

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

if ($scriptDir !== '' && $scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
    $path = substr($path, strlen($scriptDir)) ?: '/';
}

$path = '/' . trim($path, '/');
$path = $path === '/' ? '/' : rtrim($path, '/');

$public = new PublicController();
$admin = new AdminController();

if ($path === '/' && $method === 'GET') {
    $public->home();
    exit;
}

if ($path === '/about' && $method === 'GET') {
    $public->about();
    exit;
}

if ($path === '/services' && $method === 'GET') {
    $public->services();
    exit;
}

if ($path === '/projects' && $method === 'GET') {
    $public->projects();
    exit;
}

if (preg_match('#^/projects/([a-z0-9-]+)$#', $path, $matches) && $method === 'GET') {
    $public->project($matches[1]);
    exit;
}

if ($path === '/contact' && $method === 'GET') {
    $public->contact();
    exit;
}

if ($path === '/contact' && $method === 'POST') {
    $public->submitContact();
    exit;
}

if ($path === '/privacy-policy' && $method === 'GET') {
    $public->contentPage('privacy-policy', 'privacy');
    exit;
}

if ($path === '/terms-conditions' && $method === 'GET') {
    $public->contentPage('terms-conditions', 'terms');
    exit;
}

if ($path === '/admin/login' && $method === 'GET') {
    $admin->loginForm();
    exit;
}

if ($path === '/admin/login' && $method === 'POST') {
    $admin->login();
    exit;
}

if ($path === '/admin/logout' && $method === 'POST') {
    Auth::requireAdmin();
    $admin->logout();
    exit;
}

if (str_starts_with($path, '/admin')) {
    Auth::requireAdmin();

    $routes = [
        ['GET', '#^/admin$#', 'dashboard'],
        ['GET|POST', '#^/admin/settings$#', 'settings'],
        ['GET|POST', '#^/admin/hero$#', 'hero'],
        ['GET|POST', '#^/admin/about$#', 'about'],
        ['GET', '#^/admin/services$#', 'services'],
        ['GET|POST', '#^/admin/services/create$#', 'serviceCreate'],
        ['GET|POST', '#^/admin/services/(\d+)/edit$#', 'serviceEdit'],
        ['POST', '#^/admin/services/(\d+)/delete$#', 'serviceDelete'],
        ['GET', '#^/admin/projects$#', 'projects'],
        ['GET|POST', '#^/admin/projects/create$#', 'projectCreate'],
        ['GET|POST', '#^/admin/projects/(\d+)/edit$#', 'projectEdit'],
        ['POST', '#^/admin/projects/(\d+)/delete$#', 'projectDelete'],
        ['GET|POST', '#^/admin/skills$#', 'skills'],
        ['GET|POST', '#^/admin/process$#', 'process'],
        ['GET', '#^/admin/testimonials$#', 'testimonials'],
        ['GET|POST', '#^/admin/testimonials/create$#', 'testimonialCreate'],
        ['GET|POST', '#^/admin/testimonials/(\d+)/edit$#', 'testimonialEdit'],
        ['POST', '#^/admin/testimonials/(\d+)/delete$#', 'testimonialDelete'],
        ['GET|POST', '#^/admin/enquiries$#', 'enquiries'],
        ['GET', '#^/admin/seo$#', 'seo'],
        ['GET|POST', '#^/admin/seo/(\d+)/edit$#', 'seoEdit'],
        ['GET', '#^/admin/pages$#', 'pages'],
        ['GET|POST', '#^/admin/pages/(\d+)/edit$#', 'pageEdit'],
        ['GET|POST', '#^/admin/media$#', 'media'],
        ['GET|POST', '#^/admin/password$#', 'password'],
    ];

    foreach ($routes as [$verbs, $pattern, $handler]) {
        if (!in_array($method, explode('|', $verbs), true)) {
            continue;
        }

        if (preg_match($pattern, $path, $matches)) {
            array_shift($matches);
            $admin->{$handler}(...array_map('intval', $matches));
            exit;
        }
    }
}

$public->notFound();
