<?php $isEdit = !empty($service); ?>
<form class="admin-panel admin-form" action="<?= $isEdit ? url('/admin/services/' . $service['id'] . '/edit') : url('/admin/services/create') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-grid two">
        <label>Title <input name="title" value="<?= e($service['title'] ?? '') ?>" required></label>
        <label>Slug <input name="slug" value="<?= e($service['slug'] ?? '') ?>" placeholder="auto-generated if empty"></label>
        <label>Icon Text <input name="icon_label" value="<?= e($service['icon_label'] ?? '') ?>" placeholder="WP"></label>
        <label>Position <input type="number" name="position" value="<?= e($service['position'] ?? 0) ?>"></label>
    </div>
    <label>Description <textarea name="description" rows="5" required><?= e($service['description'] ?? '') ?></textarea></label>
    <label>Service Icon Image <input type="file" name="icon_path" accept="image/png,image/jpeg,image/webp,image/gif"></label>
    <?php if (!empty($service['icon_path'])): ?><img class="preview" src="<?= e(asset($service['icon_path'])) ?>" alt="Icon preview"><?php endif; ?>
    <label><span><input type="checkbox" name="is_active" value="1" <?= checked($service['is_active'] ?? 1) ?>> Active</span></label>
    <div class="admin-actions">
        <button class="btn" type="submit"><?= $isEdit ? 'Save Service' : 'Create Service' ?></button>
        <a class="btn btn-ghost" href="<?= url('/admin/services') ?>">Cancel</a>
    </div>
</form>

