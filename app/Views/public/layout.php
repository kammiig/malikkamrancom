<?php
$siteName = $settings['site_name'] ?? 'Muhammad Kamran Malik';
$logoText = $settings['logo_text'] ?? $siteName;
$metaTitle = $seo['meta_title'] ?: $siteName;
$metaDescription = $seo['meta_description'] ?? '';
$ogImage = $seo['og_image'] ?? '';
$ogImageUrl = $ogImage ? (str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage)) : '';
$logoPath = $settings['logo_path'] ?? '';
$faviconPath = $settings['favicon_path'] ?? '';
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
    <?php if ($faviconPath): ?>
        <link rel="icon" href="<?= e(asset($faviconPath)) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= asset('/assets/css/app.css') ?>">
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <header class="site-header" data-header>
        <div class="container header-inner">
            <a class="brand" href="<?= url('/') ?>" aria-label="<?= e($siteName) ?>">
                <?php if ($logoPath): ?>
                    <img class="brand-logo" src="<?= e(asset($logoPath)) ?>" alt="<?= e($settings['logo_alt'] ?? $siteName) ?>" title="<?= e($settings['logo_title'] ?? $siteName) ?>" width="180" height="44">
                <?php else: ?>
                    <span class="brand-mark">KM</span>
                    <span><?= e($logoText) ?></span>
                <?php endif; ?>
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
                    <?php if ($logoPath): ?>
                        <img class="brand-logo" src="<?= e(asset($logoPath)) ?>" alt="<?= e($settings['logo_alt'] ?? $siteName) ?>" title="<?= e($settings['logo_title'] ?? $siteName) ?>" width="180" height="44" loading="lazy">
                    <?php else: ?>
                        <span class="brand-mark">KM</span>
                        <span><?= e($logoText) ?></span>
                    <?php endif; ?>
                </a>
                <p><?= e($settings['footer_about'] ?? '') ?></p>
                <div class="social-row">
                    <?php foreach ($socialLinks as $social): ?>
                        <a class="social-link" href="<?= e($social['url']) ?>" target="_blank" rel="noopener" aria-label="<?= e($social['label']) ?>">
                            <?= icon_svg($social['icon'] ?: $social['label'], 'social-icon') ?>
                            <span><?= e($social['label']) ?></span>
                        </a>
                    <?php endforeach; ?>
                    <?php if (!empty($settings['contact_email'])): ?>
                        <a class="social-link" href="mailto:<?= e($settings['contact_email']) ?>" aria-label="Email">
                            <?= icon_svg('email', 'social-icon') ?>
                            <span>Email</span>
                        </a>
                    <?php endif; ?>
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
            </div>
        </div>
        <div class="container footer-bottom">
            <span><?= e($settings['copyright_text'] ?? '© 2026 Muhammad Kamran Malik. All Rights Reserved.') ?></span>
            <nav class="footer-legal" aria-label="Legal links">
                <a href="<?= e(url($settings['privacy_link'] ?? '/privacy-policy')) ?>">Privacy Policy</a>
                <span aria-hidden="true">|</span>
                <a href="<?= e(url($settings['terms_link'] ?? '/terms-conditions')) ?>">Terms & Conditions</a>
            </nav>
        </div>
    </footer>

    <script src="<?= asset('/assets/js/app.js') ?>" defer></script>
</body>
</html>
