<?php $aboutExtra = $about['extra'] ?? []; ?>
<section class="page-hero section-pad compact">
    <div class="container">
        <span class="eyebrow">About</span>
        <h1><?= e($about['title']) ?></h1>
        <p class="lead"><?= e($about['subtitle'] ?: 'Professional web development for businesses that need speed, trust, and easy content control.') ?></p>
    </div>
</section>

<section class="section-pad">
    <div class="container split">
        <div class="portrait">
            <?php if (!empty($about['image_path'])): ?>
                <img src="<?= e(asset($about['image_path'])) ?>" alt="Muhammad Kamran Malik" loading="lazy">
            <?php else: ?>
                <div class="avatar-placeholder">MKM</div>
            <?php endif; ?>
        </div>
        <div>
            <div class="rich-text"><?= nl2p($about['content']) ?></div>
            <div class="pill-cloud">
                <?php foreach (($aboutExtra['experience_details'] ?? []) as $detail): ?>
                    <span><?= e($detail) ?></span>
                <?php endforeach; ?>
            </div>
            <?php if (!empty($about['file_path'])): ?>
                <a class="btn btn-ghost" href="<?= e(asset($about['file_path'])) ?>" target="_blank" rel="noopener">Download CV</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="section-pad alt">
    <div class="container">
        <div class="section-head">
            <span class="eyebrow">Capabilities</span>
            <h2>Hands-on skills across front-end, CMS, eCommerce, hosting, and backend tooling.</h2>
        </div>
        <div class="skills-grid">
            <?php foreach ($skillGroups as $group): ?>
                <article class="skill-group">
                    <h3><?= e($group['title']) ?></h3>
                    <div class="pill-cloud">
                        <?php foreach ($group['skills'] as $skill): ?><span><?= e($skill['title']) ?></span><?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

