<?php
$extra = $section['extra'] ?? [];
$details = implode("\n", $extra['experience_details'] ?? []);
?>
<form class="admin-panel admin-form" action="<?= url('/admin/about') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <label>About Title <input name="title" value="<?= e($section['title']) ?>" required></label>
    <label>About Subtitle <input name="subtitle" value="<?= e($section['subtitle']) ?>"></label>
    <label>About Description <textarea name="content" rows="9" required><?= e($section['content']) ?></textarea></label>
    <label>Experience Details <textarea name="experience_details" rows="7"><?= e($details) ?></textarea></label>
    <div class="form-grid two">
        <label>Profile Image <input type="file" name="image" accept="image/png,image/jpeg,image/webp,image/gif"></label>
        <label>CV / Resume File <input type="file" name="resume" accept="application/pdf,.doc,.docx"></label>
    </div>
    <?php if (!empty($section['image_path'])): ?><img class="preview" src="<?= e(asset($section['image_path'])) ?>" alt="Profile preview"><?php endif; ?>
    <?php if (!empty($section['file_path'])): ?><a class="text-link" href="<?= e(asset($section['file_path'])) ?>" target="_blank" rel="noopener">Current Resume File</a><?php endif; ?>
    <div class="admin-actions">
        <button class="btn" type="submit">Save About</button>
    </div>
</form>

