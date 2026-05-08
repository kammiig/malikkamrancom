<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\Upload;
use PDO;

final class AdminController
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function loginForm(): void
    {
        if (Auth::check()) {
            redirect('/admin');
        }

        view('admin/login', ['title' => 'Admin Login'], null);
    }

    public function login(): void
    {
        Csrf::verify();

        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if (Auth::attempt($email, $password)) {
            redirect('/admin');
        }

        flash('error', 'Invalid admin email or password.');
        redirect('/admin/login');
    }

    public function logout(): void
    {
        Csrf::verify();
        Auth::logout();
        redirect('/admin/login');
    }

    public function dashboard(): void
    {
        $counts = [
            'services' => $this->count('services'),
            'projects' => $this->count('projects'),
            'testimonials' => $this->count('testimonials'),
            'unread' => $this->count('enquiries', 'is_read = 0'),
        ];
        $enquiries = $this->rows('SELECT * FROM enquiries ORDER BY created_at DESC LIMIT 6');

        $this->render('admin/dashboard', compact('counts', 'enquiries'), 'Dashboard');
    }

    public function settings(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $currentSettings = $this->settingsArray();

            $logoPath = isset($_POST['remove_logo'])
                ? ''
                : (Upload::image('logo_path', 'logo') ?: ($currentSettings['logo_path'] ?? ''));
            $faviconPath = isset($_POST['remove_favicon'])
                ? ''
                : (Upload::image('favicon_path', 'favicon') ?: ($currentSettings['favicon_path'] ?? ''));

            $fields = [
                'site_name',
                'logo_text',
                'logo_alt',
                'logo_title',
                'header_cta_text',
                'header_cta_link',
                'contact_email',
                'contact_phone',
                'contact_location',
                'footer_about',
                'copyright_text',
                'privacy_link',
                'terms_link',
            ];

            foreach ($fields as $field) {
                $this->saveSetting($field, trim((string) ($_POST[$field] ?? '')));
            }
            $this->saveSetting('logo_path', $logoPath);
            $this->saveSetting('favicon_path', $faviconPath);

            foreach ($_POST['nav_id'] ?? [] as $index => $id) {
                $this->execute(
                    'UPDATE navigation_items SET label = ?, url = ?, position = ?, is_active = ? WHERE id = ?',
                    [
                        trim((string) ($_POST['nav_label'][$index] ?? '')),
                        trim((string) ($_POST['nav_url'][$index] ?? '#')),
                        (int) ($_POST['nav_position'][$index] ?? 0),
                        isset($_POST['nav_active'][$id]) ? 1 : 0,
                        (int) $id,
                    ]
                );
            }

            foreach ($_POST['social_id'] ?? [] as $index => $id) {
                if (isset($_POST['delete_social'][$id])) {
                    $this->execute('DELETE FROM social_links WHERE id = ?', [(int) $id]);
                    continue;
                }

                $this->execute(
                    'UPDATE social_links SET label = ?, url = ?, icon = ?, position = ?, is_active = ? WHERE id = ?',
                    [
                        trim((string) ($_POST['social_label'][$index] ?? '')),
                        trim((string) ($_POST['social_url'][$index] ?? '')),
                        trim((string) ($_POST['social_icon'][$index] ?? '')),
                        (int) ($_POST['social_position'][$index] ?? 0),
                        isset($_POST['social_active'][$id]) ? 1 : 0,
                        (int) $id,
                    ]
                );
            }

            $newLabel = trim((string) ($_POST['new_social_label'] ?? ''));
            $newUrl = trim((string) ($_POST['new_social_url'] ?? ''));
            if ($newLabel !== '' && $newUrl !== '') {
                $this->execute(
                    'INSERT INTO social_links (label, url, icon, position, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, 1, NOW(), NOW())',
                    [$newLabel, $newUrl, trim((string) ($_POST['new_social_icon'] ?? '')), (int) ($_POST['new_social_position'] ?? 99)]
                );
            }

            flash('success', 'Site settings updated.');
            redirect('/admin/settings');
        }

        $settings = $this->settingsArray();
        $navItems = $this->rows('SELECT * FROM navigation_items ORDER BY position, id');
        $socialLinks = $this->rows('SELECT * FROM social_links ORDER BY position, id');

        $this->render('admin/settings', compact('settings', 'navItems', 'socialLinks'), 'Site Settings');
    }

    public function hero(): void
    {
        $section = $this->section('hero');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $image = Upload::image('image', 'hero') ?: ($section['image_path'] ?? '');
            if (isset($_POST['remove_image'])) {
                $image = '';
            }
            $extra = [
                'primary_button_text' => trim((string) ($_POST['primary_button_text'] ?? '')),
                'primary_button_link' => trim((string) ($_POST['primary_button_link'] ?? '')),
                'secondary_button_text' => trim((string) ($_POST['secondary_button_text'] ?? '')),
                'secondary_button_link' => trim((string) ($_POST['secondary_button_link'] ?? '')),
                'enable_image' => isset($_POST['enable_image']),
                'image_alt' => trim((string) ($_POST['image_alt'] ?? '')),
                'image_title' => trim((string) ($_POST['image_title'] ?? '')),
                'stats' => $this->parseStats((string) ($_POST['stats'] ?? '')),
            ];

            $this->saveSection('hero', [
                'title' => trim((string) ($_POST['title'] ?? '')),
                'subtitle' => trim((string) ($_POST['subtitle'] ?? '')),
                'content' => '',
                'image_path' => $image,
                'file_path' => '',
                'extra_json' => json_encode($extra, JSON_UNESCAPED_SLASHES),
            ]);

            flash('success', 'Hero section updated.');
            redirect('/admin/hero');
        }

        $this->render('admin/hero', compact('section'), 'Hero Section');
    }

    public function about(): void
    {
        $section = $this->section('about');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $image = Upload::image('image', 'about') ?: ($section['image_path'] ?? '');
            if (isset($_POST['remove_image'])) {
                $image = '';
            }
            $resume = Upload::document('resume', 'resume') ?: ($section['file_path'] ?? '');
            $extra = [
                'experience_details' => $this->parseLines((string) ($_POST['experience_details'] ?? '')),
                'image_style' => in_array($_POST['image_style'] ?? 'black-white', ['normal', 'black-white', 'dark-overlay'], true)
                    ? (string) $_POST['image_style']
                    : 'black-white',
                'image_alt' => trim((string) ($_POST['image_alt'] ?? '')),
                'image_title' => trim((string) ($_POST['image_title'] ?? '')),
            ];

            $this->saveSection('about', [
                'title' => trim((string) ($_POST['title'] ?? '')),
                'subtitle' => trim((string) ($_POST['subtitle'] ?? '')),
                'content' => trim((string) ($_POST['content'] ?? '')),
                'image_path' => $image,
                'file_path' => $resume,
                'extra_json' => json_encode($extra, JSON_UNESCAPED_SLASHES),
            ]);

            flash('success', 'About section updated.');
            redirect('/admin/about');
        }

        $this->render('admin/about', compact('section'), 'About Section');
    }

    public function services(): void
    {
        $services = $this->rows('SELECT * FROM services ORDER BY position, title');
        $this->render('admin/services/index', compact('services'), 'Services');
    }

    public function serviceCreate(): void
    {
        $this->serviceForm();
    }

    public function serviceEdit(int $id): void
    {
        $this->serviceForm($id);
    }

    public function serviceDelete(int $id): void
    {
        Csrf::verify();
        $this->execute('DELETE FROM services WHERE id = ?', [$id]);
        flash('success', 'Service deleted.');
        redirect('/admin/services');
    }

    public function projects(): void
    {
        $projects = $this->rows('SELECT * FROM projects ORDER BY position, created_at DESC');
        $this->render('admin/projects/index', compact('projects'), 'Projects');
    }

    public function projectCreate(): void
    {
        $this->projectForm();
    }

    public function projectEdit(int $id): void
    {
        $this->projectForm($id);
    }

    public function projectDelete(int $id): void
    {
        Csrf::verify();
        $this->execute('DELETE FROM project_images WHERE project_id = ?', [$id]);
        $this->execute('DELETE FROM projects WHERE id = ?', [$id]);
        flash('success', 'Project deleted.');
        redirect('/admin/projects');
    }

    public function skills(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $action = (string) ($_POST['action'] ?? '');

            if ($action === 'save_categories') {
                foreach ($_POST['category_id'] ?? [] as $index => $id) {
                    $this->execute(
                        'UPDATE skill_categories SET title = ?, position = ?, updated_at = NOW() WHERE id = ?',
                        [trim((string) ($_POST['category_title'][$index] ?? '')), (int) ($_POST['category_position'][$index] ?? 0), (int) $id]
                    );
                }
                flash('success', 'Skill categories updated.');
            }

            if ($action === 'add_category') {
                $title = trim((string) ($_POST['new_category_title'] ?? ''));
                if ($title !== '') {
                    $this->execute('INSERT INTO skill_categories (title, position, created_at, updated_at) VALUES (?, ?, NOW(), NOW())', [$title, (int) ($_POST['new_category_position'] ?? 99)]);
                }
                flash('success', 'Skill category added.');
            }

            if ($action === 'delete_category') {
                $id = (int) ($_POST['category_id_delete'] ?? 0);
                $this->execute('DELETE FROM skills WHERE category_id = ?', [$id]);
                $this->execute('DELETE FROM skill_categories WHERE id = ?', [$id]);
                flash('success', 'Skill category deleted.');
            }

            if ($action === 'save_skills') {
                foreach ($_POST['skill_id'] ?? [] as $index => $id) {
                    $this->execute(
                        'UPDATE skills SET title = ?, category_id = ?, position = ?, updated_at = NOW() WHERE id = ?',
                        [
                            trim((string) ($_POST['skill_title'][$index] ?? '')),
                            (int) ($_POST['skill_category_id'][$index] ?? 0),
                            (int) ($_POST['skill_position'][$index] ?? 0),
                            (int) $id,
                        ]
                    );
                }
                flash('success', 'Skills updated.');
            }

            if ($action === 'add_skill') {
                $title = trim((string) ($_POST['new_skill_title'] ?? ''));
                if ($title !== '') {
                    $this->execute(
                        'INSERT INTO skills (category_id, title, position, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())',
                        [(int) ($_POST['new_skill_category_id'] ?? 0), $title, (int) ($_POST['new_skill_position'] ?? 99)]
                    );
                }
                flash('success', 'Skill added.');
            }

            if ($action === 'delete_skill') {
                $this->execute('DELETE FROM skills WHERE id = ?', [(int) ($_POST['skill_id_delete'] ?? 0)]);
                flash('success', 'Skill deleted.');
            }

            redirect('/admin/skills');
        }

        $categories = $this->rows('SELECT * FROM skill_categories ORDER BY position, title');
        $skills = $this->rows('SELECT skills.*, skill_categories.title AS category_title FROM skills LEFT JOIN skill_categories ON skill_categories.id = skills.category_id ORDER BY skill_categories.position, skills.position, skills.title');
        $this->render('admin/skills', compact('categories', 'skills'), 'Skills');
    }

    public function process(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $action = (string) ($_POST['action'] ?? '');

            if ($action === 'save') {
                foreach ($_POST['step_id'] ?? [] as $index => $id) {
                    $this->execute(
                        'UPDATE process_steps SET step_number = ?, title = ?, description = ?, position = ?, updated_at = NOW() WHERE id = ?',
                        [
                            trim((string) ($_POST['step_number'][$index] ?? '')),
                            trim((string) ($_POST['title'][$index] ?? '')),
                            trim((string) ($_POST['description'][$index] ?? '')),
                            (int) ($_POST['position'][$index] ?? 0),
                            (int) $id,
                        ]
                    );
                }
                flash('success', 'Process steps updated.');
            }

            if ($action === 'add') {
                $this->execute(
                    'INSERT INTO process_steps (step_number, title, description, position, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())',
                    [
                        trim((string) ($_POST['new_step_number'] ?? '')),
                        trim((string) ($_POST['new_title'] ?? '')),
                        trim((string) ($_POST['new_description'] ?? '')),
                        (int) ($_POST['new_position'] ?? 99),
                    ]
                );
                flash('success', 'Process step added.');
            }

            if ($action === 'delete') {
                $this->execute('DELETE FROM process_steps WHERE id = ?', [(int) ($_POST['delete_id'] ?? 0)]);
                flash('success', 'Process step deleted.');
            }

            redirect('/admin/process');
        }

        $steps = $this->rows('SELECT * FROM process_steps ORDER BY position, step_number');
        $this->render('admin/process', compact('steps'), 'Process');
    }

    public function testimonials(): void
    {
        $testimonials = $this->rows('SELECT * FROM testimonials ORDER BY position, created_at DESC');
        $this->render('admin/testimonials/index', compact('testimonials'), 'Testimonials');
    }

    public function testimonialCreate(): void
    {
        $this->testimonialForm();
    }

    public function testimonialEdit(int $id): void
    {
        $this->testimonialForm($id);
    }

    public function testimonialDelete(int $id): void
    {
        Csrf::verify();
        $this->execute('DELETE FROM testimonials WHERE id = ?', [$id]);
        flash('success', 'Testimonial deleted.');
        redirect('/admin/testimonials');
    }

    public function enquiries(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $action = (string) ($_POST['action'] ?? '');
            $id = (int) ($_POST['id'] ?? 0);

            if ($action === 'toggle') {
                $this->execute('UPDATE enquiries SET is_read = 1 - is_read WHERE id = ?', [$id]);
                flash('success', 'Enquiry status updated.');
            }

            if ($action === 'delete') {
                $this->execute('DELETE FROM enquiries WHERE id = ?', [$id]);
                flash('success', 'Enquiry deleted.');
            }

            redirect('/admin/enquiries');
        }

        $enquiries = $this->rows('SELECT * FROM enquiries ORDER BY created_at DESC');
        $this->render('admin/enquiries', compact('enquiries'), 'Enquiries');
    }

    public function seo(): void
    {
        $items = $this->rows('SELECT * FROM seo_settings ORDER BY page_key');
        $this->render('admin/seo/index', compact('items'), 'SEO Settings');
    }

    public function seoEdit(int $id): void
    {
        $item = $this->row('SELECT * FROM seo_settings WHERE id = ? LIMIT 1', [$id]);
        if (!$item) {
            redirect('/admin/seo');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $image = Upload::image('og_image', 'seo') ?: ($item['og_image'] ?? '');
            if (isset($_POST['remove_og_image'])) {
                $image = '';
            }

            $this->execute(
                'UPDATE seo_settings SET meta_title = ?, meta_description = ?, meta_keywords = ?, og_title = ?, og_description = ?, og_image = ?, og_image_alt = ?, og_image_title = ?, canonical_url = ?, updated_at = NOW() WHERE id = ?',
                [
                    trim((string) ($_POST['meta_title'] ?? '')),
                    trim((string) ($_POST['meta_description'] ?? '')),
                    trim((string) ($_POST['meta_keywords'] ?? '')),
                    trim((string) ($_POST['og_title'] ?? '')),
                    trim((string) ($_POST['og_description'] ?? '')),
                    $image,
                    trim((string) ($_POST['og_image_alt'] ?? '')),
                    trim((string) ($_POST['og_image_title'] ?? '')),
                    trim((string) ($_POST['canonical_url'] ?? '')),
                    $id,
                ]
            );

            flash('success', 'SEO settings updated.');
            redirect('/admin/seo');
        }

        $this->render('admin/seo/form', compact('item'), 'Edit SEO');
    }

    public function pages(): void
    {
        $pages = $this->rows('SELECT * FROM content_pages ORDER BY title');
        $this->render('admin/pages/index', compact('pages'), 'Content Pages');
    }

    public function pageEdit(int $id): void
    {
        $page = $this->row('SELECT * FROM content_pages WHERE id = ? LIMIT 1', [$id]);
        if (!$page) {
            redirect('/admin/pages');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $this->execute(
                'UPDATE content_pages SET title = ?, body = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
                [
                    trim((string) ($_POST['title'] ?? '')),
                    trim((string) ($_POST['body'] ?? '')),
                    isset($_POST['is_active']) ? 1 : 0,
                    $id,
                ]
            );
            flash('success', 'Page updated.');
            redirect('/admin/pages');
        }

        $this->render('admin/pages/form', compact('page'), 'Edit Page');
    }

    public function media(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $action = (string) ($_POST['action'] ?? '');
            $id = (int) ($_POST['id'] ?? 0);
            $file = $this->row('SELECT * FROM uploaded_files WHERE id = ? LIMIT 1', [$id]);

            if (!$file) {
                flash('error', 'Media file not found.');
                redirect('/admin/media');
            }

            if ($action === 'update') {
                $this->execute(
                    'UPDATE uploaded_files SET alt_text = ?, title_text = ? WHERE id = ?',
                    [trim((string) ($_POST['alt_text'] ?? '')), trim((string) ($_POST['title_text'] ?? '')), $id]
                );
                flash('success', 'Media metadata updated.');
            }

            if ($action === 'replace') {
                $replacement = Upload::imageDetails('replacement', 'media-replacement');
                if ($replacement !== null) {
                    $this->deletePublicFile((string) $file['path']);
                    $this->execute(
                        'UPDATE uploaded_files SET original_name = ?, path = ?, mime_type = ?, file_size = ?, alt_text = ?, title_text = ? WHERE id = ?',
                        [
                            $replacement['original_name'],
                            $replacement['path'],
                            $replacement['mime_type'],
                            $replacement['file_size'],
                            trim((string) ($_POST['alt_text'] ?? $file['alt_text'] ?? '')),
                            trim((string) ($_POST['title_text'] ?? $file['title_text'] ?? '')),
                            $id,
                        ]
                    );
                    $this->execute('DELETE FROM uploaded_files WHERE path = ? AND id != ?', [$replacement['path'], $id]);
                    flash('success', 'Media file replaced.');
                }
            }

            if ($action === 'delete') {
                $this->deletePublicFile((string) $file['path']);
                $this->execute('DELETE FROM uploaded_files WHERE id = ?', [$id]);
                flash('success', 'Media file deleted from the media library.');
            }

            redirect('/admin/media');
        }

        $files = $this->rows('SELECT * FROM uploaded_files ORDER BY created_at DESC');
        $this->render('admin/media', compact('files'), 'Media Library');
    }

    public function password(): void
    {
        $user = Auth::user();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $current = (string) ($_POST['current_password'] ?? '');
            $password = (string) ($_POST['password'] ?? '');
            $confirm = (string) ($_POST['password_confirmation'] ?? '');

            $record = $this->row('SELECT * FROM users WHERE id = ? LIMIT 1', [$user['id'] ?? 0]);
            if (!$record || !password_verify($current, $record['password_hash'])) {
                flash('error', 'Current password is incorrect.');
                redirect('/admin/password');
            }

            if (strlen($password) < 10 || $password !== $confirm) {
                flash('error', 'New password must be at least 10 characters and match the confirmation.');
                redirect('/admin/password');
            }

            $this->execute('UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?', [password_hash($password, PASSWORD_DEFAULT), $user['id']]);
            flash('success', 'Password changed.');
            redirect('/admin/password');
        }

        $this->render('admin/password', compact('user'), 'Change Password');
    }

    private function serviceForm(?int $id = null): void
    {
        $service = $id ? $this->row('SELECT * FROM services WHERE id = ? LIMIT 1', [$id]) : null;
        if ($id && !$service) {
            redirect('/admin/services');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $image = Upload::image('icon_path', 'service') ?: ($service['icon_path'] ?? '');
            if (isset($_POST['remove_icon'])) {
                $image = '';
            }
            $slug = $this->uniqueSlug('services', trim((string) ($_POST['slug'] ?? '')), trim((string) ($_POST['title'] ?? '')), $id);
            $params = [
                trim((string) ($_POST['title'] ?? '')),
                $slug,
                trim((string) ($_POST['description'] ?? '')),
                $image,
                trim((string) ($_POST['icon_label'] ?? '')),
                trim((string) ($_POST['icon_alt'] ?? '')),
                trim((string) ($_POST['icon_title'] ?? '')),
                (int) ($_POST['position'] ?? 0),
                isset($_POST['is_active']) ? 1 : 0,
            ];

            if ($id) {
                $this->execute(
                    'UPDATE services SET title = ?, slug = ?, description = ?, icon_path = ?, icon_label = ?, icon_alt = ?, icon_title = ?, position = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
                    [...$params, $id]
                );
                flash('success', 'Service updated.');
            } else {
                $this->execute(
                    'INSERT INTO services (title, slug, description, icon_path, icon_label, icon_alt, icon_title, position, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
                    $params
                );
                flash('success', 'Service created.');
            }

            redirect('/admin/services');
        }

        $this->render('admin/services/form', compact('service'), $id ? 'Edit Service' : 'Add Service');
    }

    private function projectForm(?int $id = null): void
    {
        $project = $id ? $this->row('SELECT * FROM projects WHERE id = ? LIMIT 1', [$id]) : null;
        if ($id && !$project) {
            redirect('/admin/projects');
        }
        $gallery = $id ? $this->rows('SELECT * FROM project_images WHERE project_id = ? ORDER BY position, id', [$id]) : [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $image = Upload::image('image_path', 'project') ?: ($project['image_path'] ?? '');
            if (isset($_POST['remove_image'])) {
                $image = '';
            }
            $slug = $this->uniqueSlug('projects', trim((string) ($_POST['slug'] ?? '')), trim((string) ($_POST['title'] ?? '')), $id);
            $params = [
                trim((string) ($_POST['title'] ?? '')),
                $slug,
                trim((string) ($_POST['category'] ?? '')),
                trim((string) ($_POST['short_description'] ?? '')),
                trim((string) ($_POST['overview'] ?? '')),
                trim((string) ($_POST['client_problem'] ?? '')),
                trim((string) ($_POST['solution'] ?? '')),
                trim((string) ($_POST['key_features'] ?? '')),
                trim((string) ($_POST['results'] ?? '')),
                trim((string) ($_POST['tech_stack'] ?? '')),
                $image,
                trim((string) ($_POST['image_alt'] ?? '')),
                trim((string) ($_POST['image_title'] ?? '')),
                trim((string) ($_POST['live_url'] ?? '')),
                trim((string) ($_POST['seo_title'] ?? '')),
                trim((string) ($_POST['seo_description'] ?? '')),
                (int) ($_POST['position'] ?? 0),
                isset($_POST['is_featured']) ? 1 : 0,
                isset($_POST['is_active']) ? 1 : 0,
            ];

            if ($id) {
                $this->execute(
                    'UPDATE projects SET title = ?, slug = ?, category = ?, short_description = ?, overview = ?, client_problem = ?, solution = ?, key_features = ?, results = ?, tech_stack = ?, image_path = ?, image_alt = ?, image_title = ?, live_url = ?, seo_title = ?, seo_description = ?, position = ?, is_featured = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
                    [...$params, $id]
                );
                $projectId = $id;
                flash('success', 'Project updated.');
            } else {
                $this->execute(
                    'INSERT INTO projects (title, slug, category, short_description, overview, client_problem, solution, key_features, results, tech_stack, image_path, image_alt, image_title, live_url, seo_title, seo_description, position, is_featured, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
                    $params
                );
                $projectId = (int) $this->db->lastInsertId();
                flash('success', 'Project created.');
            }

            foreach ($_POST['delete_image'] ?? [] as $imageId => $value) {
                $this->execute('DELETE FROM project_images WHERE id = ? AND project_id = ?', [(int) $imageId, $projectId]);
            }

            foreach ($_POST['gallery_id'] ?? [] as $index => $imageId) {
                $this->execute(
                    'UPDATE project_images SET caption = ?, alt_text = ?, title_text = ?, position = ?, updated_at = NOW() WHERE id = ? AND project_id = ?',
                    [
                        trim((string) ($_POST['gallery_caption'][$index] ?? '')),
                        trim((string) ($_POST['gallery_alt'][$index] ?? '')),
                        trim((string) ($_POST['gallery_title'][$index] ?? '')),
                        (int) ($_POST['gallery_position'][$index] ?? 0),
                        (int) $imageId,
                        $projectId,
                    ]
                );
            }

            $nextPosition = (int) $this->row('SELECT COALESCE(MAX(position), 0) AS max_position FROM project_images WHERE project_id = ?', [$projectId])['max_position'];
            foreach (Upload::images('gallery', 'project-gallery') as $path) {
                $nextPosition++;
                $this->execute(
                    'INSERT INTO project_images (project_id, image_path, caption, alt_text, title_text, position, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())',
                    [$projectId, $path, '', trim((string) ($_POST['new_gallery_alt'] ?? $project['title'] ?? 'Project screenshot')), trim((string) ($_POST['new_gallery_title'] ?? $project['title'] ?? 'Project screenshot')), $nextPosition]
                );
            }

            redirect('/admin/projects');
        }

        $this->render('admin/projects/form', compact('project', 'gallery'), $id ? 'Edit Project' : 'Add Project');
    }

    private function testimonialForm(?int $id = null): void
    {
        $testimonial = $id ? $this->row('SELECT * FROM testimonials WHERE id = ? LIMIT 1', [$id]) : null;
        if ($id && !$testimonial) {
            redirect('/admin/testimonials');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::verify();
            $image = Upload::image('image_path', 'testimonial') ?: ($testimonial['image_path'] ?? '');
            if (isset($_POST['remove_image'])) {
                $image = '';
            }
            $params = [
                trim((string) ($_POST['quote'] ?? '')),
                trim((string) ($_POST['client_name'] ?? '')),
                trim((string) ($_POST['client_role'] ?? '')),
                trim((string) ($_POST['company'] ?? '')),
                (int) ($_POST['rating'] ?? 5),
                $image,
                trim((string) ($_POST['image_alt'] ?? '')),
                trim((string) ($_POST['image_title'] ?? '')),
                (int) ($_POST['position'] ?? 0),
                isset($_POST['is_active']) ? 1 : 0,
            ];

            if ($id) {
                $this->execute(
                    'UPDATE testimonials SET quote = ?, client_name = ?, client_role = ?, company = ?, rating = ?, image_path = ?, image_alt = ?, image_title = ?, position = ?, is_active = ?, updated_at = NOW() WHERE id = ?',
                    [...$params, $id]
                );
                flash('success', 'Testimonial updated.');
            } else {
                $this->execute(
                    'INSERT INTO testimonials (quote, client_name, client_role, company, rating, image_path, image_alt, image_title, position, is_active, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
                    $params
                );
                flash('success', 'Testimonial created.');
            }

            redirect('/admin/testimonials');
        }

        $this->render('admin/testimonials/form', compact('testimonial'), $id ? 'Edit Testimonial' : 'Add Testimonial');
    }

    private function render(string $view, array $data, string $title): void
    {
        Auth::requireAdmin();
        $adminUser = Auth::user();
        view($view, array_merge($data, compact('title', 'adminUser')), 'admin/layout');
    }

    private function rows(string $sql, array $params = []): array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll();
    }

    private function row(string $sql, array $params = []): ?array
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
        $row = $statement->fetch();

        return $row ?: null;
    }

    private function execute(string $sql, array $params = []): void
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($params);
    }

    private function count(string $table, string $where = '1 = 1'): int
    {
        $row = $this->row("SELECT COUNT(*) AS total FROM {$table} WHERE {$where}");

        return (int) ($row['total'] ?? 0);
    }

    private function settingsArray(): array
    {
        $settings = [];
        foreach ($this->rows('SELECT `key`, `value` FROM settings') as $row) {
            $settings[$row['key']] = $row['value'];
        }

        return $settings;
    }

    private function saveSetting(string $key, string $value): void
    {
        $this->execute(
            'INSERT INTO settings (`key`, `value`, created_at, updated_at) VALUES (?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = NOW()',
            [$key, $value]
        );
    }

    private function section(string $key): array
    {
        $section = $this->row('SELECT * FROM sections WHERE section_key = ? LIMIT 1', [$key]) ?: [
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

    private function saveSection(string $key, array $data): void
    {
        $this->execute(
            'INSERT INTO sections (section_key, title, subtitle, content, image_path, file_path, extra_json, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW()) ON DUPLICATE KEY UPDATE title = VALUES(title), subtitle = VALUES(subtitle), content = VALUES(content), image_path = VALUES(image_path), file_path = VALUES(file_path), extra_json = VALUES(extra_json), updated_at = NOW()',
            [
                $key,
                $data['title'],
                $data['subtitle'],
                $data['content'],
                $data['image_path'],
                $data['file_path'],
                $data['extra_json'],
            ]
        );
    }

    private function deletePublicFile(string $path): void
    {
        if ($path === '' || str_contains($path, '..') || str_starts_with($path, 'http')) {
            return;
        }

        $absolute = APP_ROOT . '/public/' . ltrim($path, '/');
        if (is_file($absolute)) {
            @unlink($absolute);
        }
    }

    private function parseLines(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split("/\R/", $value) ?: [])));
    }

    private function parseStats(string $value): array
    {
        $stats = [];
        foreach ($this->parseLines($value) as $line) {
            [$number, $label] = array_pad(array_map('trim', explode('|', $line, 2)), 2, '');
            if ($number !== '' || $label !== '') {
                $stats[] = ['number' => $number, 'label' => $label];
            }
        }

        return $stats;
    }

    private function uniqueSlug(string $table, string $preferred, string $title, ?int $ignoreId = null): string
    {
        $base = str_slug($preferred !== '' ? $preferred : $title);
        $slug = $base;
        $index = 2;

        while (true) {
            $sql = "SELECT id FROM {$table} WHERE slug = ?";
            $params = [$slug];
            if ($ignoreId !== null) {
                $sql .= ' AND id != ?';
                $params[] = $ignoreId;
            }
            $sql .= ' LIMIT 1';

            if (!$this->row($sql, $params)) {
                return $slug;
            }

            $slug = $base . '-' . $index;
            $index++;
        }
    }
}
