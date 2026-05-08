<section class="admin-panel">
    <table class="admin-table">
        <thead><tr><th>Contact</th><th>Project</th><th>Message</th><th>Status</th><th>Date</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($enquiries as $enquiry): ?>
            <tr>
                <td>
                    <strong><?= e($enquiry['full_name']) ?></strong><br>
                    <a class="text-link" href="mailto:<?= e($enquiry['email']) ?>"><?= e($enquiry['email']) ?></a><br>
                    <span><?= e($enquiry['phone']) ?></span>
                </td>
                <td><?= e($enquiry['project_type']) ?><br><span><?= e($enquiry['budget_range']) ?></span></td>
                <td><?= nl2br(e($enquiry['message'])) ?></td>
                <td><span class="status <?= $enquiry['is_read'] ? '' : 'off' ?>"><?= $enquiry['is_read'] ? 'Read' : 'Unread' ?></span></td>
                <td><?= e($enquiry['created_at']) ?></td>
                <td>
                    <div class="admin-actions">
                        <form action="<?= url('/admin/enquiries') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="toggle">
                            <input type="hidden" name="id" value="<?= e($enquiry['id']) ?>">
                            <button class="btn btn-ghost btn-small" type="submit">Toggle Read</button>
                        </form>
                        <form action="<?= url('/admin/enquiries') ?>" method="post" data-confirm="Delete this enquiry?">
                            <?= csrf_field() ?>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= e($enquiry['id']) ?>">
                            <button class="btn btn-ghost btn-small" type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

