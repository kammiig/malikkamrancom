<?php
$heroExtra = $hero['extra'] ?? [];
$stats = $heroExtra['stats'] ?? [];
$aboutExtra = $about['extra'] ?? [];
$heroImageEnabled = !empty($heroExtra['enable_image']) && !empty($hero['image_path']);
$aboutImageStyle = $aboutExtra['image_style'] ?? 'black-white';
?>
<section class="hero section-pad">
    <div class="container hero-grid">
        <div class="hero-copy reveal">
            <span class="eyebrow">Web Developer • WordPress • Shopify • WHMCS • Laravel</span>
            <h1><?= e($hero['title']) ?></h1>
            <p class="lead"><?= e($hero['subtitle']) ?></p>
            <div class="hero-actions">
                <a class="btn" href="<?= e(url($heroExtra['primary_button_link'] ?? '/projects')) ?>"><?= e($heroExtra['primary_button_text'] ?? 'View My Work') ?></a>
                <a class="btn btn-ghost" href="<?= e(url($heroExtra['secondary_button_link'] ?? '/contact')) ?>"><?= e($heroExtra['secondary_button_text'] ?? 'Let’s Work Together') ?></a>
            </div>
            <div class="stats-row">
                <?php foreach ($stats as $stat): ?>
                    <div>
                        <strong><?= e($stat['number'] ?? '') ?></strong>
                        <span><?= e($stat['label'] ?? '') ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="hero-visual reveal">
            <?php if ($heroImageEnabled): ?>
                <img src="<?= e(asset($hero['image_path'])) ?>" alt="<?= e($heroExtra['image_alt'] ?? 'Muhammad Kamran Malik web developer') ?>" title="<?= e($heroExtra['image_title'] ?? 'Muhammad Kamran Malik') ?>" width="520" height="650" loading="eager" fetchpriority="high">
            <?php else: ?>
                <div class="code-card">
                    <div class="code-dots"><span></span><span></span><span></span></div>
                    <pre><code>const developer = {
  name: "Muhammad Kamran Malik",
  focus: ["fast", "modern", "editable"],
  stack: ["PHP", "Laravel", "WordPress"],
};</code></pre>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section-pad" id="about">
    <div class="container split">
        <div class="portrait image-style-<?= e($aboutImageStyle) ?> reveal">
            <?php if (!empty($about['image_path'])): ?>
                <img src="<?= e(asset($about['image_path'])) ?>" alt="<?= e($aboutExtra['image_alt'] ?? 'Muhammad Kamran Malik profile image') ?>" title="<?= e($aboutExtra['image_title'] ?? 'Muhammad Kamran Malik') ?>" width="560" height="700" loading="lazy">
            <?php else: ?>
                <div class="avatar-placeholder">MKM</div>
            <?php endif; ?>
        </div>
        <div class="reveal">
            <span class="eyebrow">About</span>
            <h2><?= e($about['title']) ?></h2>
            <div class="rich-text"><?= nl2p($about['content']) ?></div>
            <div class="pill-cloud">
                <?php foreach (($aboutExtra['experience_details'] ?? []) as $detail): ?>
                    <span><?= e($detail) ?></span>
                <?php endforeach; ?>
            </div>
            <?php if (!empty($about['file_path'])): ?>
                <a class="text-link" href="<?= e(asset($about['file_path'])) ?>" target="_blank" rel="noopener">Download CV</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section-pad alt" id="services">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Services</span>
            <h2>Web development services built around business outcomes.</h2>
        </div>
        <div class="card-grid services-grid">
            <?php foreach ($services as $service): ?>
                <article class="service-card reveal">
                    <div class="service-icon">
                        <?php if (!empty($service['icon_path'])): ?>
                            <img src="<?= e(asset($service['icon_path'])) ?>" alt="<?= e($service['icon_alt'] ?? $service['title']) ?>" title="<?= e($service['icon_title'] ?? $service['title']) ?>" width="32" height="32" loading="lazy">
                        <?php else: ?>
                            <?= icon_svg($service['icon_label'] ?: 'monitor', 'service-svg') ?>
                        <?php endif; ?>
                    </div>
                    <h3><?= e($service['title']) ?></h3>
                    <p><?= e($service['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad" id="projects">
    <div class="container">
        <div class="section-head with-action">
            <div>
                <span class="eyebrow">Featured Projects</span>
                <h2>Selected work across hosting, dashboards, eCommerce, and business websites.</h2>
            </div>
            <a class="btn btn-ghost" href="<?= url('/projects') ?>">View All Projects</a>
        </div>
        <div class="project-grid">
            <?php foreach ($projects as $project): ?>
                <?php $tags = array_filter(array_map('trim', explode(',', (string) $project['tech_stack']))); ?>
                <article class="project-card reveal">
                    <a class="project-media" href="<?= e(url('/projects/' . $project['slug'])) ?>">
                        <?php if (!empty($project['image_path'])): ?>
                            <img src="<?= e(asset($project['image_path'])) ?>" alt="<?= e($project['image_alt'] ?: $project['title']) ?>" title="<?= e($project['image_title'] ?: $project['title']) ?>" width="640" height="400" loading="lazy">
                        <?php else: ?>
                            <span><?= e($project['category']) ?></span>
                        <?php endif; ?>
                    </a>
                    <div class="project-body">
                        <span class="category"><?= e($project['category']) ?></span>
                        <h3><?= e($project['title']) ?></h3>
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

<section class="section-pad alt" id="skills">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Skills</span>
            <h2>A practical stack for fast, editable, business-ready websites.</h2>
        </div>
        <div class="skills-grid">
            <?php foreach ($skillGroups as $group): ?>
                <article class="skill-group reveal">
                    <h3><?= e($group['title']) ?></h3>
                    <div class="pill-cloud">
                        <?php foreach ($group['skills'] as $skill): ?>
                            <span><?= e($skill['title']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad" id="process">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Process</span>
            <h2>A clear, calm workflow from first idea to launch.</h2>
        </div>
        <div class="timeline">
            <?php foreach ($processSteps as $step): ?>
                <article class="timeline-item reveal">
                    <span><?= e($step['step_number']) ?></span>
                    <h3><?= e($step['title']) ?></h3>
                    <p><?= e($step['description']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad alt" id="testimonials">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Testimonials</span>
            <h2>Reliable delivery, clean communication, and websites clients can manage.</h2>
        </div>
        <div class="testimonial-grid">
            <?php foreach ($testimonials as $testimonial): ?>
                <article class="testimonial-card reveal">
                    <div class="rating"><?= str_repeat('★', (int) $testimonial['rating']) ?></div>
                    <blockquote>“<?= e($testimonial['quote']) ?>”</blockquote>
                    <div class="client-row">
                        <?php if (!empty($testimonial['image_path'])): ?>
                            <img src="<?= e(asset($testimonial['image_path'])) ?>" alt="<?= e($testimonial['image_alt'] ?: $testimonial['client_name']) ?>" title="<?= e($testimonial['image_title'] ?: $testimonial['client_name']) ?>" width="46" height="46" loading="lazy">
                        <?php endif; ?>
                        <div>
                            <strong><?= e($testimonial['client_name']) ?></strong>
                            <span><?= e(trim($testimonial['client_role'] . ' ' . $testimonial['company'])) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section-pad contact-band" id="contact">
    <div class="container contact-grid">
        <div>
            <span class="eyebrow">Contact</span>
            <h2>Have a website, store, dashboard, or WHMCS project in mind?</h2>
            <p class="lead">Send the details and I’ll reply with a clear next step, timeline, and practical recommendation.</p>
        </div>
        <?php require APP_ROOT . '/app/Views/partials/contact-form.php'; ?>
    </div>
</section>
