<section class="admin-panel">
    <form class="admin-form" action="<?= url('/admin/process') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save">
        <table class="admin-table">
            <thead><tr><th>Step</th><th>Title</th><th>Description</th><th>Position</th></tr></thead>
            <tbody>
            <?php foreach ($steps as $step): ?>
                <tr>
                    <td>
                        <input type="hidden" name="step_id[]" value="<?= e($step['id']) ?>">
                        <input name="step_number[]" value="<?= e($step['step_number']) ?>">
                    </td>
                    <td><input name="title[]" value="<?= e($step['title']) ?>"></td>
                    <td><textarea name="description[]" rows="3"><?= e($step['description']) ?></textarea></td>
                    <td><input type="number" name="position[]" value="<?= e($step['position']) ?>"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn" type="submit">Save Steps</button>
    </form>
</section>

<section class="admin-panel">
    <h2>Add Process Step</h2>
    <form class="admin-form" action="<?= url('/admin/process') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add">
        <div class="form-grid two">
            <label>Step Number <input name="new_step_number" placeholder="Step 07"></label>
            <label>Title <input name="new_title"></label>
            <label>Position <input type="number" name="new_position" value="99"></label>
        </div>
        <label>Description <textarea name="new_description" rows="4"></textarea></label>
        <button class="btn btn-ghost" type="submit">Add Step</button>
    </form>
</section>

<section class="admin-panel">
    <h2>Delete Process Step</h2>
    <form class="admin-form" action="<?= url('/admin/process') ?>" method="post" data-confirm="Delete this process step?">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="delete">
        <label>Step
            <select name="delete_id">
                <?php foreach ($steps as $step): ?><option value="<?= e($step['id']) ?>"><?= e($step['step_number'] . ' ' . $step['title']) ?></option><?php endforeach; ?>
            </select>
        </label>
        <button class="btn btn-ghost" type="submit">Delete Step</button>
    </form>
</section>

