<div class="admin-actions">
    <a class="btn" href="<?= url('/admin/testimonials/create') ?>">Add Testimonial</a>
</div>
<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Client</th><th>Quote</th><th>Rating</th><th>Status</th><th>Position</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($testimonials as $testimonial): ?>
            <tr>
                <td><?= e($testimonial['client_name']) ?><br><span><?= e(trim($testimonial['client_role'] . ' ' . $testimonial['company'])) ?></span></td>
                <td><?= e($testimonial['quote']) ?></td>
                <td><?= e($testimonial['rating']) ?>/5</td>
                <td><span class="status <?= $testimonial['is_active'] ? '' : 'off' ?>"><?= $testimonial['is_active'] ? 'Active' : 'Hidden' ?></span></td>
                <td><?= e($testimonial['position']) ?></td>
                <td>
                    <div class="admin-actions">
                        <a class="btn btn-ghost btn-small" href="<?= url('/admin/testimonials/' . $testimonial['id'] . '/edit') ?>">Edit</a>
                        <form action="<?= url('/admin/testimonials/' . $testimonial['id'] . '/delete') ?>" method="post" data-confirm="Delete this testimonial?">
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

