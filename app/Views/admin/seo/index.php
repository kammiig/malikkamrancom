<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Page</th><th>Path</th><th>Meta Title</th><th>Description</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= e($item['page_key']) ?></td>
                <td><?= e($item['path']) ?></td>
                <td><?= e($item['meta_title']) ?></td>
                <td><?= e($item['meta_description']) ?></td>
                <td><a class="btn btn-ghost btn-small" href="<?= url('/admin/seo/' . $item['id'] . '/edit') ?>">Edit SEO</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

