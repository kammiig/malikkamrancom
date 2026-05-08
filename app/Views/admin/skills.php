<section class="admin-panel">
    <h2>Skill Categories</h2>
    <form class="admin-form" action="<?= url('/admin/skills') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save_categories">
        <table class="admin-table">
            <thead><tr><th>Title</th><th>Position</th></tr></thead>
            <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td>
                        <input type="hidden" name="category_id[]" value="<?= e($category['id']) ?>">
                        <input name="category_title[]" value="<?= e($category['title']) ?>">
                    </td>
                    <td><input type="number" name="category_position[]" value="<?= e($category['position']) ?>"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn" type="submit">Save Categories</button>
    </form>

    <form class="admin-form" action="<?= url('/admin/skills') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add_category">
        <div class="form-grid two">
            <label>New Category <input name="new_category_title"></label>
            <label>Position <input type="number" name="new_category_position" value="99"></label>
        </div>
        <button class="btn btn-ghost" type="submit">Add Category</button>
    </form>

    <form class="admin-form" action="<?= url('/admin/skills') ?>" method="post" data-confirm="Delete this category and all skills inside it?">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="delete_category">
        <div class="form-grid two">
            <label>Delete Category
                <select name="category_id_delete">
                    <?php foreach ($categories as $category): ?><option value="<?= e($category['id']) ?>"><?= e($category['title']) ?></option><?php endforeach; ?>
                </select>
            </label>
        </div>
        <button class="btn btn-ghost" type="submit">Delete Category</button>
    </form>
</section>

<section class="admin-panel">
    <h2>Skills</h2>
    <form class="admin-form" action="<?= url('/admin/skills') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="save_skills">
        <table class="admin-table">
            <thead><tr><th>Skill</th><th>Category</th><th>Position</th></tr></thead>
            <tbody>
            <?php foreach ($skills as $skill): ?>
                <tr>
                    <td>
                        <input type="hidden" name="skill_id[]" value="<?= e($skill['id']) ?>">
                        <input name="skill_title[]" value="<?= e($skill['title']) ?>">
                    </td>
                    <td>
                        <select name="skill_category_id[]">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= e($category['id']) ?>" <?= selected($skill['category_id'], $category['id']) ?>><?= e($category['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="skill_position[]" value="<?= e($skill['position']) ?>"></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn" type="submit">Save Skills</button>
    </form>

    <form class="admin-form" action="<?= url('/admin/skills') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="add_skill">
        <div class="form-grid two">
            <label>New Skill <input name="new_skill_title"></label>
            <label>Category
                <select name="new_skill_category_id">
                    <?php foreach ($categories as $category): ?><option value="<?= e($category['id']) ?>"><?= e($category['title']) ?></option><?php endforeach; ?>
                </select>
            </label>
            <label>Position <input type="number" name="new_skill_position" value="99"></label>
        </div>
        <button class="btn btn-ghost" type="submit">Add Skill</button>
    </form>

    <form class="admin-form" action="<?= url('/admin/skills') ?>" method="post" data-confirm="Delete this skill?">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="delete_skill">
        <div class="form-grid two">
            <label>Delete Skill
                <select name="skill_id_delete">
                    <?php foreach ($skills as $skill): ?><option value="<?= e($skill['id']) ?>"><?= e($skill['title']) ?></option><?php endforeach; ?>
                </select>
            </label>
        </div>
        <button class="btn btn-ghost" type="submit">Delete Skill</button>
    </form>
</section>

