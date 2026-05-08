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
    <label>Stats <textarea name="stats" rows="5" placeholder="8+|Years Experience"><?= e(implode("\n", $statsLines)) ?></textarea></label>
    <label>Hero Image or Avatar <input type="file" name="image" accept="image/png,image/jpeg,image/webp,image/gif"></label>
    <?php if (!empty($section['image_path'])): ?><img class="preview" src="<?= e(asset($section['image_path'])) ?>" alt="Hero preview"><?php endif; ?>
    <div class="admin-actions">
        <button class="btn" type="submit">Save Hero</button>
    </div>
</form>

