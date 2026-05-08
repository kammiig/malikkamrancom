<?php $isEdit = !empty($service); ?>
<form class="admin-panel admin-form" action="<?= $isEdit ? url('/admin/services/' . $service['id'] . '/edit') : url('/admin/services/create') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-grid two">
        <label>Title <input name="title" value="<?= e($service['title'] ?? '') ?>" required></label>
        <label>Slug <input name="slug" value="<?= e($service['slug'] ?? '') ?>" placeholder="auto-generated if empty"></label>
        <label>Service Icon
            <select name="icon_label">
                <?php foreach (service_icon_options() as $key => $label): ?>
                    <option value="<?= e($key) ?>" <?= selected($service['icon_label'] ?? 'monitor', $key) ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Position <input type="number" name="position" value="<?= e($service['position'] ?? 0) ?>"></label>
        <label>Icon Alt Text <input name="icon_alt" value="<?= e($service['icon_alt'] ?? '') ?>" placeholder="Service icon description"></label>
        <label>Icon Title <input name="icon_title" value="<?= e($service['icon_title'] ?? '') ?>" placeholder="<?= e($service['title'] ?? 'Service icon') ?>"></label>
    </div>
    <label>Description <textarea name="description" rows="5" required><?= e($service['description'] ?? '') ?></textarea></label>
    <label>Optional Custom Service Icon Image <input type="file" name="icon_path" accept="image/png,image/jpeg,image/webp,image/gif"></label>
    <?php if (!empty($service['icon_path'])): ?>
        <label>
            <img class="preview" src="<?= e(asset($service['icon_path'])) ?>" alt="<?= e($service['icon_alt'] ?? 'Icon preview') ?>">
            <span><input type="checkbox" name="remove_icon" value="1"> Remove custom icon image</span>
        </label>
    <?php endif; ?>
    <label><span><input type="checkbox" name="is_active" value="1" <?= checked($service['is_active'] ?? 1) ?>> Active</span></label>
    <div class="admin-actions">
        <button class="btn" type="submit"><?= $isEdit ? 'Save Service' : 'Create Service' ?></button>
        <a class="btn btn-ghost" href="<?= url('/admin/services') ?>">Cancel</a>
    </div>
</form>
