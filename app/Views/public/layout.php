<?php
$siteName = $settings['site_name'] ?? 'Muhammad Kamran Malik';
$logoText = $settings['logo_text'] ?? $siteName;
$metaTitle = $seo['meta_title'] ?: $siteName;
$metaDescription = $seo['meta_description'] ?? '';
$ogImage = $seo['og_image'] ?? '';
$ogImageUrl = $ogImage ? (str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage)) : '';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($metaTitle) ?></title>
    <meta name="description" content="<?= e($metaDescription) ?>">
    <?php if (!empty($seo['meta_keywords'])): ?>
        <meta name="keywords" content="<?= e($seo['meta_keywords']) ?>">
    <?php endif; ?>
    <link rel="canonical" href="<?= e($seo['canonical_url'] ?? url($currentPath ?? '/')) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($seo['og_title'] ?: $metaTitle) ?>">
    <meta property="og:description" content="<?= e($seo['og_description'] ?: $metaDescription) ?>">
    <?php if ($ogImageUrl): ?>
        <meta property="og:image" content="<?= e($ogImageUrl) ?>">
    <?php endif; ?>
    <meta name="theme-color" content="#06111f">
    <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header" data-header>
        <div class="container header-inner">
            <a class="brand" href="<?= url('/') ?>" aria-label="<?= e($siteName) ?>">
                <span class="brand-mark">KM</span>
                <span><?= e($logoText) ?></span>
            </a>

            <button class="nav-toggle" type="button" aria-label="Open navigation" data-nav-toggle>
                <span></span><span></span><span></span>
            </button>

            <nav class="main-nav" data-nav>
                <?php foreach ($navItems as $item): ?>
                    <a href="<?= e(url($item['url'])) ?>"><?= e($item['label']) ?></a>
                <?php endforeach; ?>
                <a class="btn btn-small" href="<?= e(url($settings['header_cta_link'] ?? '/contact')) ?>">
                    <?= e($settings['header_cta_text'] ?? 'Hire Me') ?>
                </a>
            </nav>
        </div>
    </header>

    <main id="main">
        <?= $content ?>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div>
                <a class="brand footer-brand" href="<?= url('/') ?>">
                    <span class="brand-mark">KM</span>
                    <span><?= e($logoText) ?></span>
                </a>
                <p><?= e($settings['footer_about'] ?? '') ?></p>
                <div class="social-row">
                    <?php foreach ($socialLinks as $social): ?>
                        <a href="<?= e($social['url']) ?>" target="_blank" rel="noopener">
                            <?= e($social['icon'] ?: $social['label']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <div>
                <h3>Quick Links</h3>
                <?php foreach ($navItems as $item): ?>
                    <a href="<?= e(url($item['url'])) ?>"><?= e($item['label']) ?></a>
                <?php endforeach; ?>
            </div>
            <div>
                <h3>Services</h3>
                <?php foreach (($footerServices ?? []) as $service): ?>
                    <a href="<?= url('/services') ?>"><?= e($service['title']) ?></a>
                <?php endforeach; ?>
            </div>
            <div>
                <h3>Contact</h3>
                <a href="mailto:<?= e($settings['contact_email'] ?? '') ?>"><?= e($settings['contact_email'] ?? '') ?></a>
                <span><?= e($settings['contact_phone'] ?? '') ?></span>
                <span><?= e($settings['contact_location'] ?? '') ?></span>
                <a href="<?= url('/privacy-policy') ?>">Privacy Policy</a>
                <a href="<?= url('/terms-conditions') ?>">Terms & Conditions</a>
            </div>
        </div>
        <div class="container footer-bottom">
            <span><?= e($settings['copyright_text'] ?? '© 2026 Muhammad Kamran Malik. All Rights Reserved.') ?></span>
        </div>
    </footer>

    <script src="<?= asset('/assets/js/app.js') ?>" defer></script>
</body>
</html>
