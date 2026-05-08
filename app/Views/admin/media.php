<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Preview</th><th>File</th><th>Context</th><th>Size</th><th>Uploaded</th></tr></thead>
        <tbody>
        <?php foreach ($files as $file): ?>
            <tr>
                <td>
                    <?php if (str_starts_with($file['mime_type'], 'image/')): ?>
                        <img class="preview" src="<?= e(asset($file['path'])) ?>" alt="<?= e($file['original_name']) ?>">
                    <?php else: ?>
                        <a class="text-link" href="<?= e(asset($file['path'])) ?>" target="_blank" rel="noopener">Open File</a>
                    <?php endif; ?>
                </td>
                <td><?= e($file['original_name']) ?><br><span><?= e($file['path']) ?></span></td>
                <td><?= e($file['context']) ?></td>
                <td><?= e(number_format((int) $file['file_size'] / 1024, 1)) ?> KB</td>
                <td><?= e($file['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

