<?php $tags = array_filter(array_map('trim', explode(',', (string) $project['tech_stack']))); ?>
<section class="page-hero section-pad compact">
    <div class="container">
        <span class="eyebrow"><?= e($project['category']) ?></span>
        <h1><?= e($project['title']) ?></h1>
        <p class="lead"><?= e($project['short_description']) ?></p>
        <div class="hero-actions">
            <?php if (!empty($project['live_url'])): ?>
                <a class="btn" href="<?= e($project['live_url']) ?>" target="_blank" rel="noopener">Live Website</a>
            <?php endif; ?>
            <a class="btn btn-ghost" href="<?= url('/projects') ?>">Back to Projects</a>
        </div>
    </div>
</section>

<section class="section-pad">
    <div class="container case-grid">
        <aside class="case-sidebar">
            <h2>Tech Stack</h2>
            <div class="tag-row">
                <?php foreach ($tags as $tag): ?><span><?= e($tag) ?></span><?php endforeach; ?>
            </div>
        </aside>
        <div class="case-content">
            <?php if (!empty($project['image_path'])): ?>
                <img class="case-cover" src="<?= e(asset($project['image_path'])) ?>" alt="<?= e($project['image_alt'] ?: $project['title']) ?>" title="<?= e($project['image_title'] ?: $project['title']) ?>" width="920" height="575" loading="lazy">
            <?php endif; ?>
            <article>
                <h2>Overview</h2>
                <div class="rich-text"><?= nl2p($project['overview']) ?></div>
            </article>
            <article>
                <h2>Client Problem</h2>
                <div class="rich-text"><?= nl2p($project['client_problem']) ?></div>
            </article>
            <article>
                <h2>My Solution</h2>
                <div class="rich-text"><?= nl2p($project['solution']) ?></div>
            </article>
            <article>
                <h2>Key Features</h2>
                <div class="rich-text"><?= nl2p($project['key_features']) ?></div>
            </article>
            <article>
                <h2>Results</h2>
                <div class="rich-text"><?= nl2p($project['results']) ?></div>
            </article>
        </div>
    </div>
</section>

<?php if ($gallery): ?>
    <section class="section-pad alt">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Screenshots</span>
                <h2>Project gallery</h2>
            </div>
            <div class="gallery-grid">
                <?php foreach ($gallery as $image): ?>
                    <figure>
                        <img src="<?= e(asset($image['image_path'])) ?>" alt="<?= e($image['alt_text'] ?: ($image['caption'] ?: $project['title'])) ?>" title="<?= e($image['title_text'] ?: ($image['caption'] ?: $project['title'])) ?>" width="640" height="400" loading="lazy">
                        <?php if (!empty($image['caption'])): ?><figcaption><?= e($image['caption']) ?></figcaption><?php endif; ?>
                    </figure>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
