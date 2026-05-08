<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login</title>
    <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>">
</head>
<body class="login-page">
    <form class="login-card admin-form" action="<?= url('/admin/login') ?>" method="post">
        <?= csrf_field() ?>
        <a class="brand" href="<?= url('/') ?>">
            <span class="brand-mark">KM</span>
            <span>Muhammad Kamran Malik</span>
        </a>
        <div>
            <h1>Admin Login</h1>
            <p class="lead">Sign in to manage portfolio content, projects, SEO, enquiries, and uploads.</p>
        </div>
        <?php if ($message = flash('error')): ?><div class="notice error"><?= e($message) ?></div><?php endif; ?>
        <label>Email <input type="email" name="email" required autofocus></label>
        <label>Password <input type="password" name="password" required></label>
        <button class="btn" type="submit">Login</button>
    </form>
</body>
</html>

