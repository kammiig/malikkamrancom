<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Title</th><th>Slug</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($pages as $page): ?>
            <tr>
                <td><?= e($page['title']) ?></td>
                <td><?= e($page['slug']) ?></td>
                <td><span class="status <?= $page['is_active'] ? '' : 'off' ?>"><?= $page['is_active'] ? 'Active' : 'Hidden' ?></span></td>
                <td><a class="btn btn-ghost btn-small" href="<?= url('/admin/pages/' . $page['id'] . '/edit') ?>">Edit</a></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

