<?php
$extra = $section['extra'] ?? [];
$statsLines = [];
foreach (($extra['stats'] ?? []) as $stat) {
    $statsLines[] = ($stat['number'] ?? '') . '|' . ($stat['label'] ?? '');
}
?>
<form class="admin-panel admin-form" action="<?= url('/admin/hero') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <label>Hero Headline <input name="title" value="<?= e($section['title']) ?>" required></label>
    <label>Subheading <textarea name="subtitle" rows="5" required><?= e($section['subtitle']) ?></textarea></label>
    <div class="form-grid two">
        <label>Primary Button Text <input name="primary_button_text" value="<?= e($extra['primary_button_text'] ?? '') ?>"></label>
        <label>Primary Button Link <input name="primary_button_link" value="<?= e($extra['primary_button_link'] ?? '') ?>"></label>
        <label>Secondary Button Text <input name="secondary_button_text" value="<?= e($extra['secondary_button_text'] ?? '') ?>"></label>
        <label>Secondary Button Link <input name="secondary_button_link" value="<?= e($extra['secondary_button_link'] ?? '') ?>"></label>
    </div>
    <label>Stats <textarea name="stats" rows="5" placeholder="8+|Years Experience&#10;8K+|Successful Business Websites Created"><?= e(implode("\n", $statsLines)) ?></textarea></label>

    <h2>Hero Image</h2>
    <div class="form-grid two">
        <label>Hero Image or Avatar <input type="file" name="image" accept="image/png,image/jpeg,image/webp,image/gif"></label>
        <label>Hero Image Alt Text <input name="image_alt" value="<?= e($extra['image_alt'] ?? '') ?>" placeholder="Muhammad Kamran Malik web developer"></label>
        <label>Hero Image Title <input name="image_title" value="<?= e($extra['image_title'] ?? '') ?>" placeholder="Muhammad Kamran Malik"></label>
        <label><span><input type="checkbox" name="enable_image" value="1" <?= checked($extra['enable_image'] ?? 0) ?>> Enable uploaded hero image</span></label>
    </div>
    <?php if (!empty($section['image_path'])): ?>
        <label>
            <img class="preview" src="<?= e(asset($section['image_path'])) ?>" alt="<?= e($extra['image_alt'] ?? 'Hero preview') ?>">
            <span><input type="checkbox" name="remove_image" value="1"> Remove current hero image</span>
        </label>
    <?php endif; ?>
    <p class="admin-hint">If the image is disabled or removed, the website shows the default developer code card.</p>
    <div class="admin-actions">
        <button class="btn" type="submit">Save Hero</button>
    </div>
</form>
