<form class="admin-panel admin-form" action="<?= url('/admin/pages/' . $page['id'] . '/edit') ?>" method="post">
    <?= csrf_field() ?>
    <div class="form-grid two">
        <label>Title <input name="title" value="<?= e($page['title']) ?>" required></label>
        <label>Slug <input value="<?= e($page['slug']) ?>" disabled></label>
    </div>
    <label>Body <textarea name="body" rows="14" required><?= e($page['body']) ?></textarea></label>
    <label><span><input type="checkbox" name="is_active" value="1" <?= checked($page['is_active']) ?>> Active</span></label>
    <div class="admin-actions">
        <button class="btn" type="submit">Save Page</button>
        <a class="btn btn-ghost" href="<?= url('/admin/pages') ?>">Cancel</a>
    </div>
</form>

