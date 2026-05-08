<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Captcha;
use App\Core\Csrf;
use App\Core\Env;
use App\Core\Mailer;
use PDO;

final class PublicController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function home(): void
    {
        $hero = $this->section('hero');
        $about = $this->section('about');
        $services = $this->rows('SELECT * FROM services WHERE is_active = 1 ORDER BY position, title LIMIT 6');
        $projects = $this->rows('SELECT * FROM projects WHERE is_active = 1 AND is_featured = 1 ORDER BY position, created_at DESC LIMIT 6');
        $skillGroups = $this->skillGroups();
        $processSteps = $this->rows('SELECT * FROM process_steps ORDER BY position, step_number');
        $testimonials = $this->rows('SELECT * FROM testimonials WHERE is_active = 1 ORDER BY position, created_at DESC');

        $this->render('public/home', [
            'hero' => $hero,
            'about' => $about,
            'services' => $services,
            'projects' => $projects,
            'skillGroups' => $skillGroups,
            'processSteps' => $processSteps,
            'testimonials' => $testimonials,
            'captchaQuestion' => Captcha::question(),
        ], 'home');
    }

    public function about(): void
    {
        $about = $this->section('about');
        $skillGroups = $this->skillGroups();

        $this->render('public/about', [
            'about' => $about,
            'skillGroups' => $skillGroups,
        ], 'about');
    }

    public function services(): void
    {
        $services = $this->rows('SELECT * FROM services WHERE is_active = 1 ORDER BY position, title');
        $processSteps = $this->rows('SELECT * FROM process_steps ORDER BY position, step_number');

        $this->render('public/services', [
            'services' => $services,
            'processSteps' => $processSteps,
        ], 'services');
    }

    public function projects(): void
    {
        $projects = $this->rows('SELECT * FROM projects WHERE is_active = 1 ORDER BY position, created_at DESC');

        $this->render('public/projects', [
            'projects' => $projects,
        ], 'projects');
    }

    public function project(string $slug): void
    {
        $statement = $this->db->prepare('SELECT * FROM projects WHERE slug = ? AND is_active = 1 LIMIT 1');
        $statement->execute([$slug]);
        $project = $statement->fetch();

        if (!$project) {
            $this->notFound();
            return;
        }

        $gallery = $this->rows('SELECT * FROM project_images WHERE project_id = ? ORDER BY position, id', [$project['id']]);
        $seo = [
            'meta_title' => ($project['seo_title'] ?: $project['title']) . ' | Case Study',
            'meta_description' => $project['seo_description'] ?: $project['short_description'],
            'meta_keywords' => $project['tech_stack'],
            'og_title' => $project['seo_title'] ?: $project['title'],
            'og_description' => $project['seo_description'] ?: $project['short_description'],
            'og_image' => $project['image_path'],
            'canonical_url' => url('/projects/' . $project['slug']),
        ];

        $this->render('public/project-show', [
            'project' => $project,
            'gallery' => $gallery,
            'seoOverride' => $seo,
        ], 'project');
    }

    public function contact(): void
    {
        $this->render('public/contact', [
            'captchaQuestion' => Captcha::question(),
        ], 'contact');
    }

    public function submitContact(): void
    {
        Csrf::verify();

        $data = [
            'full_name' => trim((string) ($_POST['full_name'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'phone' => trim((string) ($_POST['phone'] ?? '')),
            'project_type' => trim((string) ($_POST['project_type'] ?? '')),
            'budget_range' => trim((string) ($_POST['budget_range'] ?? '')),
            'message' => trim((string) ($_POST['message'] ?? '')),
            'captcha' => trim((string) ($_POST['captcha'] ?? '')),
        ];

        $errors = [];
        foreach (['full_name', 'email', 'project_type', 'budget_range', 'message'] as $field) {
            if ($data[$field] === '') {
                $errors[] = 'Please complete all required fields.';
                break;
            }
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (!Captcha::verify($data['captcha'])) {
            $errors[] = 'The spam protection answer was not correct.';
        }

        if ($errors !== []) {
            remember_old($data);
            flash('error', implode(' ', array_unique($errors)));
            redirect('/contact#contact-form');
        }

        $statement = $this->db->prepare(
            'INSERT INTO enquiries (full_name, email, phone, project_type, budget_range, message, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())'
        );
        $statement->execute([
            $data['full_name'],
            $data['email'],
            $data['phone'],
            $data['project_type'],
            $data['budget_range'],
            $data['message'],
            $_SERVER['REMOTE_ADDR'] ?? '',
            substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
        ]);

        $to = (string) setting('contact_email', Env::get('ADMIN_EMAIL', ''));
        if ($to !== '' && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $body = "New portfolio enquiry\n\n"
                . "Name: {$data['full_name']}\n"
                . "Email: {$data['email']}\n"
                . "Phone: {$data['phone']}\n"
                . "Project Type: {$data['project_type']}\n"
                . "Budget: {$data['budget_range']}\n\n"
                . "Message:\n{$data['message']}\n";

            Mailer::send($to, 'New portfolio enquiry from ' . $data['full_name'], $body, $data['email']);
        }

        clear_old();
        flash('success', 'Thanks, your enquiry has been sent. I will get back to you soon.');
        redirect('/contact#contact-form');
    }

    public function contentPage(string $slug, string $pageKey): void
    {
        $statement = $this->db->prepare('SELECT * FROM content_pages WHERE slug = ? AND is_active = 1 LIMIT 1');
        $statement->execute([$slug]);
        $page = $statement->fetch();

        if (!$page) {
            $this->notFound();
            return;
        }

        $this->render('public/content-page', [
            'page' => $page,
        ], $pageKey);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->render('public/404', [], '404');
    }

    private function render(string $view, array $data, string $pageKey): void
    {
        $globals = $this->globals($pageKey, $data['seoOverride'] ?? null);
        unset($data['seoOverride']);
        view($view, array_merge($globals, $data));
    }

    private function globals(string $pageKey, ?array $seoOverride = null): array
    {
        $settings = $this->keyValue('SELECT `key`, `value` FROM settings');
        $navItems = $this->rows('SELECT * FROM navigation_items WHERE is_active = 1 ORDER BY position, id');
        $socialLinks = $this->rows('SELECT * FROM social_links WHERE is_active = 1 ORDER BY position, id');
        $footerServices = $this->rows('SELECT title FROM services WHERE is_active = 1 ORDER BY position, title LIMIT 4');
        $seo = $seoOverride ?: $this->seo($pageKey);
        $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

        if (empty($seo['canonical_url'])) {
            $seo['canonical_url'] = url($currentPath);
        }

        return compact('settings', 'navItems', 'socialLinks', 'footerServices', 'seo', 'pageKey', 'currentPath');
    }

    private function seo(string $pageKey): array
    {
        $statement = $this->db->prepare('SELECT * FROM seo_settings WHERE page_key = ? LIMIT 1');
        $statement->execute([$pageKey]);
        $seo = $statement->fetch() ?: [];

        return [
            'meta_title' => $seo['meta_title'] ?? setting('site_name', 'Muhammad Kamran Malik'),
            'meta_description' => $seo['meta_description'] ?? '',
            'meta_keywords' => $seo['meta_keywords'] ?? '',
            'og_title' => $seo['og_title'] ?? ($seo['meta_title'] ?? ''),
            'og_description' => $seo['og_description'] ?? ($seo['meta_description'] ?? ''),
            'og_image' => $seo['og_image'] ?? '',
            'canonical_url' => $seo['canonical_url'] ?? '',
        ];
    }

    private function section(string $key): array
    {
        $statement = $this->db->prepare('SELECT * FROM sections WHERE section_key = ? LIMIT 1');
        $statement->execute([$key]);
        $section = $statement->fetch() ?: [
            'section_key' => $key,
            'title' => '',
            'subtitle' => '',
            'content' => '',
            'image_path' => '',
            'file_path' => '',
            'extra_json' => '{}',
        ];
        $section['extra'] = json_decode((string) ($section['extra_json'] ?? '{}'), true) ?: [];

        return $section;
    }

    private function skillGroups(): array
    {
        $categories = $this->rows('SELECT * FROM skill_categories ORDER BY position, title');
        foreach ($categories as &$category) {
            $category['skills'] = $this->rows('SELECT * FROM skills WHERE category_id = ? ORDER BY position, title', [$category['id']]);
        }

        return $categories;
    }

    private function rows(string $sql, array $params = []): array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    private function keyValue(string $sql): array
    {
        $items = [];
        foreach ($this->rows($sql) as $row) {
            $items[$row['key']] = $row['value'];
        }

        return $items;
    }
}
