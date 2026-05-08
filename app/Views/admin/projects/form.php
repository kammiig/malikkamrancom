<?php $isEdit = !empty($project); ?>
<form class="admin-panel admin-form" action="<?= $isEdit ? url('/admin/projects/' . $project['id'] . '/edit') : url('/admin/projects/create') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-grid two">
        <label>Title <input name="title" value="<?= e($project['title'] ?? '') ?>" required></label>
        <label>Slug <input name="slug" value="<?= e($project['slug'] ?? '') ?>" placeholder="auto-generated if empty"></label>
        <label>Category <input name="category" value="<?= e($project['category'] ?? '') ?>" required></label>
        <label>Live Website Link <input name="live_url" value="<?= e($project['live_url'] ?? '') ?>"></label>
        <label>Tech Stack <input name="tech_stack" value="<?= e($project['tech_stack'] ?? '') ?>" placeholder="WordPress, PHP, cPanel"></label>
        <label>Position <input type="number" name="position" value="<?= e($project['position'] ?? 0) ?>"></label>
        <label>Cover Image Alt Text <input name="image_alt" value="<?= e($project['image_alt'] ?? '') ?>" placeholder="Project screenshot alt text"></label>
        <label>Cover Image Title <input name="image_title" value="<?= e($project['image_title'] ?? '') ?>" placeholder="<?= e($project['title'] ?? 'Project screenshot') ?>"></label>
    </div>
    <label>Short Description <textarea name="short_description" rows="4" required><?= e($project['short_description'] ?? '') ?></textarea></label>
    <label>Overview <textarea name="overview" rows="5"><?= e($project['overview'] ?? '') ?></textarea></label>
    <label>Client Problem <textarea name="client_problem" rows="5"><?= e($project['client_problem'] ?? '') ?></textarea></label>
    <label>My Solution <textarea name="solution" rows="5"><?= e($project['solution'] ?? '') ?></textarea></label>
    <label>Key Features <textarea name="key_features" rows="7"><?= e($project['key_features'] ?? '') ?></textarea></label>
    <label>Results <textarea name="results" rows="5"><?= e($project['results'] ?? '') ?></textarea></label>
    <div class="form-grid two">
        <label>SEO Title <input name="seo_title" value="<?= e($project['seo_title'] ?? '') ?>"></label>
        <label>SEO Description <input name="seo_description" value="<?= e($project['seo_description'] ?? '') ?>"></label>
    </div>
    <label>Project Screenshot / Cover <input type="file" name="image_path" accept="image/png,image/jpeg,image/webp,image/gif"></label>
    <?php if (!empty($project['image_path'])): ?>
        <label>
            <img class="preview" src="<?= e(asset($project['image_path'])) ?>" alt="<?= e($project['image_alt'] ?? 'Project cover preview') ?>">
            <span><input type="checkbox" name="remove_image" value="1"> Remove current cover image</span>
        </label>
    <?php endif; ?>
    <label>Gallery Screenshots <input type="file" name="gallery[]" accept="image/png,image/jpeg,image/webp,image/gif" multiple></label>
    <div class="form-grid two">
        <label>New Gallery Default Alt Text <input name="new_gallery_alt" value="<?= e($project['title'] ?? '') ?>"></label>
        <label>New Gallery Default Title <input name="new_gallery_title" value="<?= e($project['title'] ?? '') ?>"></label>
    </div>

    <?php if ($gallery): ?>
        <h2>Current Gallery</h2>
        <div class="admin-gallery-list">
            <?php foreach ($gallery as $image): ?>
                <div class="admin-media-item">
                    <input type="hidden" name="gallery_id[]" value="<?= e($image['id']) ?>">
                    <img class="preview" src="<?= e(asset($image['image_path'])) ?>" alt="<?= e($image['alt_text'] ?: 'Gallery preview') ?>">
                    <div class="form-grid two">
                        <label>Caption <input name="gallery_caption[]" value="<?= e($image['caption'] ?? '') ?>"></label>
                        <label>Position <input type="number" name="gallery_position[]" value="<?= e($image['position'] ?? 0) ?>"></label>
                        <label>Alt Text <input name="gallery_alt[]" value="<?= e($image['alt_text'] ?? '') ?>"></label>
                        <label>Image Title <input name="gallery_title[]" value="<?= e($image['title_text'] ?? '') ?>"></label>
                    </div>
                    <label><span><input type="checkbox" name="delete_image[<?= e($image['id']) ?>]" value="1"> Remove this image</span></label>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-grid two">
        <label><span><input type="checkbox" name="is_featured" value="1" <?= checked($project['is_featured'] ?? 1) ?>> Featured Project</span></label>
        <label><span><input type="checkbox" name="is_active" value="1" <?= checked($project['is_active'] ?? 1) ?>> Active</span></label>
    </div>
    <div class="admin-actions">
        <button class="btn" type="submit"><?= $isEdit ? 'Save Project' : 'Create Project' ?></button>
        <a class="btn btn-ghost" href="<?= url('/admin/projects') ?>">Cancel</a>
    </div>
</form>
