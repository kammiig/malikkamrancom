<form class="admin-panel admin-form" action="<?= url('/admin/seo/' . $item['id'] . '/edit') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-grid two">
        <label>Page Key <input value="<?= e($item['page_key']) ?>" disabled></label>
        <label>Path <input value="<?= e($item['path']) ?>" disabled></label>
    </div>
    <label>Meta Title <input name="meta_title" value="<?= e($item['meta_title']) ?>"></label>
    <label>Meta Description <textarea name="meta_description" rows="4"><?= e($item['meta_description']) ?></textarea></label>
    <label>Meta Keywords <input name="meta_keywords" value="<?= e($item['meta_keywords']) ?>"></label>
    <div class="form-grid two">
        <label>Open Graph Title <input name="og_title" value="<?= e($item['og_title']) ?>"></label>
        <label>Canonical URL <input name="canonical_url" value="<?= e($item['canonical_url']) ?>"></label>
    </div>
    <label>Open Graph Description <textarea name="og_description" rows="4"><?= e($item['og_description']) ?></textarea></label>
    <label>Open Graph Image <input type="file" name="og_image" accept="image/png,image/jpeg,image/webp,image/gif"></label>
    <?php if (!empty($item['og_image'])): ?><img class="preview" src="<?= e(asset($item['og_image'])) ?>" alt="OG preview"><?php endif; ?>
    <div class="admin-actions">
        <button class="btn" type="submit">Save SEO</button>
        <a class="btn btn-ghost" href="<?= url('/admin/seo') ?>">Cancel</a>
    </div>
</form>

