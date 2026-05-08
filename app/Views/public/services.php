<section class="page-hero section-pad compact">
    <div class="container">
        <span class="eyebrow">Services</span>
        <h1>Professional websites, stores, hosting platforms, and custom dashboards.</h1>
        <p class="lead">Focused development for businesses that need clean design, reliable forms, fast loading, and editable content.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="card-grid services-grid">
            <?php foreach ($services as $service): ?>
                <article class="service-card">
                    <div class="service-icon">
                        <?php if (!empty($service['icon_path'])): ?>
                            <img src="<?= e(asset($service['icon_path'])) ?>" alt="<?= e($service['icon_alt'] ?? $service['title']) ?>" title="<?= e($service['icon_title'] ?? $service['title']) ?>" width="32" height="32" loading="lazy">
                        <?php else: ?>
                            <?= icon_svg($service['icon_label'] ?: 'monitor', 'service-svg') ?>
                        <?php endif; ?>
                    </div>
                    <h2><?= e($service['title']) ?></h2>
                    <p><?= e($service['description']) ?></p>
                    <a class="text-link" href="<?= url('/contact') ?>">Discuss this service</a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Development Process</span>
            <h2>Every build follows a simple delivery path.</h2>
        </div>
        <div class="timeline">
            <?php foreach ($processSteps as $step): ?>
                <article class="timeline-item">
                    <span><?= e($step['step_number']) ?></span>
                    <h3><?= e($step['title']) ?></h3>
                    <p><?= e($step['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
