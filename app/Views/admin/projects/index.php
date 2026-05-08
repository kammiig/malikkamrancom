<div class="admin-actions">
    <a class="btn" href="<?= url('/admin/projects/create') ?>">Add Project</a>
</div>
<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Project</th><th>Category</th><th>Featured</th><th>Status</th><th>Position</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($projects as $project): ?>
            <tr>
                <td><?= e($project['title']) ?><br><span><?= e($project['short_description']) ?></span></td>
                <td><?= e($project['category']) ?></td>
                <td><span class="status"><?= $project['is_featured'] ? 'Featured' : 'Normal' ?></span></td>
                <td><span class="status <?= $project['is_active'] ? '' : 'off' ?>"><?= $project['is_active'] ? 'Active' : 'Hidden' ?></span></td>
                <td><?= e($project['position']) ?></td>
                <td>
                    <div class="admin-actions">
                        <a class="btn btn-ghost btn-small" href="<?= url('/projects/' . $project['slug']) ?>" target="_blank" rel="noopener">View</a>
                        <a class="btn btn-ghost btn-small" href="<?= url('/admin/projects/' . $project['id'] . '/edit') ?>">Edit</a>
                        <form action="<?= url('/admin/projects/' . $project['id'] . '/delete') ?>" method="post" data-confirm="Delete this project and its gallery records?">
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

