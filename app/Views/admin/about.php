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
        <label>Profile Image Alt Text <input name="image_alt" value="<?= e($extra['image_alt'] ?? '') ?>" placeholder="Muhammad Kamran Malik profile image"></label>
        <label>Profile Image Title <input name="image_title" value="<?= e($extra['image_title'] ?? '') ?>" placeholder="Muhammad Kamran Malik"></label>
        <label>Image Style
            <select name="image_style">
                <option value="normal" <?= selected($extra['image_style'] ?? '', 'normal') ?>>Normal image</option>
                <option value="black-white" <?= selected($extra['image_style'] ?? 'black-white', 'black-white') ?>>Black & white image</option>
                <option value="dark-overlay" <?= selected($extra['image_style'] ?? '', 'dark-overlay') ?>>Dark overlay image</option>
            </select>
        </label>
    </div>
    <?php if (!empty($section['image_path'])): ?>
        <label>
            <img class="preview" src="<?= e(asset($section['image_path'])) ?>" alt="<?= e($extra['image_alt'] ?? 'Profile preview') ?>">
            <span><input type="checkbox" name="remove_image" value="1"> Remove current profile image</span>
        </label>
    <?php endif; ?>
    <?php if (!empty($section['file_path'])): ?><a class="text-link" href="<?= e(asset($section['file_path'])) ?>" target="_blank" rel="noopener">Current Resume File</a><?php endif; ?>
    <div class="admin-actions">
        <button class="btn" type="submit">Save About</button>
    </div>
</form>
