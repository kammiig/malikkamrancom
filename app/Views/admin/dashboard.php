<div class="admin-grid">
    <div class="admin-card"><span>Services</span><strong><?= e($counts['services']) ?></strong></div>
    <div class="admin-card"><span>Projects</span><strong><?= e($counts['projects']) ?></strong></div>
    <div class="admin-card"><span>Testimonials</span><strong><?= e($counts['testimonials']) ?></strong></div>
    <div class="admin-card"><span>Unread Enquiries</span><strong><?= e($counts['unread']) ?></strong></div>
</div>

<section class="admin-panel">
    <div class="section-head with-action">
        <div>
            <span class="eyebrow">Latest Enquiries</span>
            <h2>Recent contact form submissions</h2>
        </div>
        <a class="btn btn-ghost" href="<?= url('/admin/enquiries') ?>">View All</a>
    </div>
    <table class="admin-table">
        <thead><tr><th>Name</th><th>Project</th><th>Budget</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
        <?php foreach ($enquiries as $enquiry): ?>
            <tr>
                <td><?= e($enquiry['full_name']) ?><br><span><?= e($enquiry['email']) ?></span></td>
                <td><?= e($enquiry['project_type']) ?></td>
                <td><?= e($enquiry['budget_range']) ?></td>
                <td><span class="status <?= $enquiry['is_read'] ? '' : 'off' ?>"><?= $enquiry['is_read'] ? 'Read' : 'Unread' ?></span></td>
                <td><?= e($enquiry['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>

