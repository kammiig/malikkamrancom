<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> | Admin</title>
    <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="brand" href="<?= url('/admin') ?>">
                <span class="brand-mark">KM</span>
                <span>Admin</span>
            </a>
            <nav>
                <a href="<?= url('/admin') ?>">Dashboard</a>
                <a href="<?= url('/admin/settings') ?>">Site Settings</a>
                <a href="<?= url('/admin/hero') ?>">Hero Section</a>
                <a href="<?= url('/admin/about') ?>">About Section</a>
                <a href="<?= url('/admin/services') ?>">Services</a>
                <a href="<?= url('/admin/projects') ?>">Projects</a>
                <a href="<?= url('/admin/skills') ?>">Skills</a>
                <a href="<?= url('/admin/process') ?>">Process</a>
                <a href="<?= url('/admin/testimonials') ?>">Testimonials</a>
                <a href="<?= url('/admin/enquiries') ?>">Enquiries</a>
                <a href="<?= url('/admin/seo') ?>">SEO Settings</a>
                <a href="<?= url('/admin/pages') ?>">Policy Pages</a>
                <a href="<?= url('/admin/media') ?>">Media Library</a>
                <a href="<?= url('/admin/password') ?>">Change Password</a>
                <a href="<?= url('/') ?>" target="_blank" rel="noopener">View Website</a>
                <form action="<?= url('/admin/logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button type="submit">Logout</button>
                </form>
            </nav>
        </aside>

        <main class="admin-main">
            <div class="admin-topbar">
                <div>
                    <span class="eyebrow"><?= e($adminUser['email'] ?? '') ?></span>
                    <h1><?= e($title) ?></h1>
                </div>
            </div>

            <?php if ($message = flash('success')): ?><div class="notice success"><?= e($message) ?></div><?php endif; ?>
            <?php if ($message = flash('error')): ?><div class="notice error"><?= e($message) ?></div><?php endif; ?>

            <?= $content ?>
        </main>
    </div>
    <script src="<?= asset('/assets/js/app.js') ?>" defer></script>
</body>
</html>

