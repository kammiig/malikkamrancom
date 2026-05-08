<section class="page-hero section-pad compact">
    <div class="container">
        <span class="eyebrow">Projects</span>
        <h1>Case studies from business websites, hosting platforms, shops, and dashboards.</h1>
        <p class="lead">A practical portfolio focused on useful features, clean content structure, and measurable business value.</p>
    </div>
</section>

<section class="section-pad">
    <div class="container">
        <div class="project-grid">
            <?php foreach ($projects as $project): ?>
                <?php $tags = array_filter(array_map('trim', explode(',', (string) $project['tech_stack']))); ?>
                <article class="project-card">
                    <a class="project-media" href="<?= e(url('/projects/' . $project['slug'])) ?>">
                        <?php if (!empty($project['image_path'])): ?>
                            <img src="<?= e(asset($project['image_path'])) ?>" alt="<?= e($project['title']) ?>" loading="lazy">
                        <?php else: ?>
                            <span><?= e($project['category']) ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="project-body">
                        <span class="category"><?= e($project['category']) ?></span>
                        <h2><?= e($project['title']) ?></h2>
                        <p><?= e($project['short_description']) ?></p>
                        <div class="tag-row">
                            <?php foreach ($tags as $tag): ?><span><?= e($tag) ?></span><?php endforeach; ?>
                        </div>
                        <div class="card-actions">
                            <a href="<?= e(url('/projects/' . $project['slug'])) ?>">View Case Study</a>
                            <?php if (!empty($project['live_url'])): ?>
                                <a href="<?= e($project['live_url']) ?>" target="_blank" rel="noopener">Live Website</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

