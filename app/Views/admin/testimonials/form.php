<?php $isEdit = !empty($testimonial); ?>
<form class="admin-panel admin-form" action="<?= $isEdit ? url('/admin/testimonials/' . $testimonial['id'] . '/edit') : url('/admin/testimonials/create') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <label>Quote <textarea name="quote" rows="6" required><?= e($testimonial['quote'] ?? '') ?></textarea></label>
    <div class="form-grid two">
        <label>Client Name <input name="client_name" value="<?= e($testimonial['client_name'] ?? '') ?>" required></label>
        <label>Role <input name="client_role" value="<?= e($testimonial['client_role'] ?? '') ?>"></label>
        <label>Company <input name="company" value="<?= e($testimonial['company'] ?? '') ?>"></label>
        <label>Rating <input type="number" min="1" max="5" name="rating" value="<?= e($testimonial['rating'] ?? 5) ?>"></label>
        <label>Position <input type="number" name="position" value="<?= e($testimonial['position'] ?? 0) ?>"></label>
        <label>Client Image / Logo <input type="file" name="image_path" accept="image/png,image/jpeg,image/webp,image/gif"></label>
    </div>
    <?php if (!empty($testimonial['image_path'])): ?><img class="preview" src="<?= e(asset($testimonial['image_path'])) ?>" alt="Client preview"><?php endif; ?>
    <label><span><input type="checkbox" name="is_active" value="1" <?= checked($testimonial['is_active'] ?? 1) ?>> Active</span></label>
    <div class="admin-actions">
        <button class="btn" type="submit"><?= $isEdit ? 'Save Testimonial' : 'Create Testimonial' ?></button>
        <a class="btn btn-ghost" href="<?= url('/admin/testimonials') ?>">Cancel</a>
    </div>
</form>

