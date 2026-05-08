<div class="admin-actions">
    <a class="btn" href="<?= url('/admin/services/create') ?>">Add Service</a>
</div>
<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Title</th><th>Slug</th><th>Position</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($services as $service): ?>
            <tr>
                <td><?= e($service['title']) ?><br><span><?= e($service['description']) ?></span></td>
                <td><?= e($service['slug']) ?></td>
                <td><?= e($service['position']) ?></td>
                <td><span class="status <?= $service['is_active'] ? '' : 'off' ?>"><?= $service['is_active'] ? 'Active' : 'Hidden' ?></span></td>
                <td>
                    <div class="admin-actions">
                        <a class="btn btn-ghost btn-small" href="<?= url('/admin/services/' . $service['id'] . '/edit') ?>">Edit</a>
                        <form action="<?= url('/admin/services/' . $service['id'] . '/delete') ?>" method="post" data-confirm="Delete this service?">
                            <?= csrf_field() ?>
                            <button class="btn btn-ghost btn-small" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

