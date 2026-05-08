<form class="admin-panel admin-form" action="<?= url('/admin/settings') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="form-grid two">
        <label>Site Name <input name="site_name" value="<?= e($settings['site_name'] ?? '') ?>" required></label>
        <label>Logo Text <input name="logo_text" value="<?= e($settings['logo_text'] ?? '') ?>" required></label>
        <label>Logo Alt Text <input name="logo_alt" value="<?= e($settings['logo_alt'] ?? '') ?>"></label>
        <label>Logo Title <input name="logo_title" value="<?= e($settings['logo_title'] ?? '') ?>"></label>
        <label>Header CTA Text <input name="header_cta_text" value="<?= e($settings['header_cta_text'] ?? '') ?>"></label>
        <label>Header CTA Link <input name="header_cta_link" value="<?= e($settings['header_cta_link'] ?? '') ?>"></label>
        <label>Contact Email <input type="email" name="contact_email" value="<?= e($settings['contact_email'] ?? '') ?>"></label>
        <label>Contact Phone <input name="contact_phone" value="<?= e($settings['contact_phone'] ?? '') ?>"></label>
        <label>Contact Location <input name="contact_location" value="<?= e($settings['contact_location'] ?? '') ?>"></label>
        <label>Copyright Text <input name="copyright_text" value="<?= e($settings['copyright_text'] ?? '') ?>"></label>
        <label>Privacy Policy Link <input name="privacy_link" value="<?= e($settings['privacy_link'] ?? '/privacy-policy') ?>"></label>
        <label>Terms & Conditions Link <input name="terms_link" value="<?= e($settings['terms_link'] ?? '/terms-conditions') ?>"></label>
    </div>
    <label>Footer About Text <textarea name="footer_about" rows="4"><?= e($settings['footer_about'] ?? '') ?></textarea></label>

    <h2>Logo & Favicon</h2>
    <div class="form-grid two">
        <label>Website Logo <input type="file" name="logo_path" accept="image/png,image/jpeg,image/webp,image/gif"></label>
        <label>Favicon <input type="file" name="favicon_path" accept="image/png,image/jpeg,image/webp,image/gif"></label>
    </div>
    <div class="admin-actions">
        <?php if (!empty($settings['logo_path'])): ?>
            <label><img class="preview logo-preview" src="<?= e(asset($settings['logo_path'])) ?>" alt="<?= e($settings['logo_alt'] ?? 'Logo preview') ?>"><span><input type="checkbox" name="remove_logo" value="1"> Remove current logo</span></label>
        <?php endif; ?>
        <?php if (!empty($settings['favicon_path'])): ?>
            <label><img class="preview favicon-preview" src="<?= e(asset($settings['favicon_path'])) ?>" alt="Favicon preview"><span><input type="checkbox" name="remove_favicon" value="1"> Remove current favicon</span></label>
        <?php endif; ?>
    </div>

    <h2>Navigation</h2>
    <table class="admin-table">
        <thead><tr><th>Label</th><th>URL</th><th>Position</th><th>Active</th></tr></thead>
        <tbody>
        <?php foreach ($navItems as $item): ?>
            <tr>
                <td>
                    <input type="hidden" name="nav_id[]" value="<?= e($item['id']) ?>">
                    <input name="nav_label[]" value="<?= e($item['label']) ?>">
                </td>
                <td><input name="nav_url[]" value="<?= e($item['url']) ?>"></td>
                <td><input type="number" name="nav_position[]" value="<?= e($item['position']) ?>"></td>
                <td><input type="checkbox" name="nav_active[<?= e($item['id']) ?>]" value="1" <?= checked($item['is_active']) ?>></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Social Links</h2>
    <table class="admin-table">
        <thead><tr><th>Platform Name</th><th>URL</th><th>Platform Icon</th><th>Position</th><th>Active</th><th>Delete</th></tr></thead>
        <tbody>
        <?php foreach ($socialLinks as $social): ?>
            <tr>
                <td>
                    <input type="hidden" name="social_id[]" value="<?= e($social['id']) ?>">
                    <input name="social_label[]" value="<?= e($social['label']) ?>">
                </td>
                <td><input name="social_url[]" value="<?= e($social['url']) ?>"></td>
                <td>
                    <select name="social_icon[]">
                        <?php foreach (social_icon_options() as $key => $label): ?>
                            <option value="<?= e($key) ?>" <?= selected($social['icon'], $key) ?>><?= e($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input type="number" name="social_position[]" value="<?= e($social['position']) ?>"></td>
                <td><input type="checkbox" name="social_active[<?= e($social['id']) ?>]" value="1" <?= checked($social['is_active']) ?>></td>
                <td><input type="checkbox" name="delete_social[<?= e($social['id']) ?>]" value="1"></td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td><input name="new_social_label" placeholder="LinkedIn"></td>
            <td><input name="new_social_url" placeholder="https://..."></td>
            <td>
                <select name="new_social_icon">
                    <?php foreach (social_icon_options() as $key => $label): ?>
                        <option value="<?= e($key) ?>"><?= e($label) ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td><input type="number" name="new_social_position" value="99"></td>
            <td colspan="2">New social link</td>
        </tr>
        </tbody>
    </table>

    <div class="admin-actions">
        <button class="btn" type="submit">Save Settings</button>
    </div>
</form>
