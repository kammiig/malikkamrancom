<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Preview</th><th>File</th><th>SEO Metadata</th><th>Replace</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td>
                    <?php if (str_starts_with($file['mime_type'], 'image/')): ?>
                        <img class="preview" src="<?= e(asset($file['path'])) ?>" alt="<?= e($file['alt_text'] ?: $file['original_name']) ?>">
                    <?php else: ?>
                        <a class="text-link" href="<?= e(asset($file['path'])) ?>" target="_blank" rel="noopener">Open File</a>
                    <?php endif; ?>
                </td>
                <td>
                    <?= e($file['original_name']) ?><br>
                    <span><?= e($file['path']) ?></span><br>
                    <span><?= e($file['context']) ?> • <?= e(number_format((int) $file['file_size'] / 1024, 1)) ?> KB • <?= e($file['created_at']) ?></span>
                </td>
                <td>
                    <form class="admin-form" action="<?= url('/admin/media') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= e($file['id']) ?>">
                        <label>Alt Text <input name="alt_text" value="<?= e($file['alt_text'] ?? '') ?>"></label>
                        <label>Image Title <input name="title_text" value="<?= e($file['title_text'] ?? '') ?>"></label>
                        <button class="btn btn-ghost btn-small" type="submit">Save Metadata</button>
                    </form>
                </td>
                <td>
                    <?php if (str_starts_with($file['mime_type'], 'image/')): ?>
                        <form class="admin-form" action="<?= url('/admin/media') ?>" method="post" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="replace">
                            <input type="hidden" name="id" value="<?= e($file['id']) ?>">
                            <input type="hidden" name="alt_text" value="<?= e($file['alt_text'] ?? '') ?>">
                            <input type="hidden" name="title_text" value="<?= e($file['title_text'] ?? '') ?>">
                            <label>Replacement Image <input type="file" name="replacement" accept="image/png,image/jpeg,image/webp,image/gif" required></label>
                            <button class="btn btn-ghost btn-small" type="submit">Replace Image</button>
                        </form>
                    <?php endif; ?>
                </td>
                <td>
                    <form action="<?= url('/admin/media') ?>" method="post" data-confirm="Delete this media record and file?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= e($file['id']) ?>">
                        <button class="btn btn-ghost btn-small" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
