<form class="admin-panel admin-form" action="<?= url('/admin/password') ?>" method="post">
    <?= csrf_field() ?>
    <label>Current Password <input type="password" name="current_password" required></label>
    <div class="form-grid two">
        <label>New Password <input type="password" name="password" minlength="10" required></label>
        <label>Confirm New Password <input type="password" name="password_confirmation" minlength="10" required></label>
    </div>
    <button class="btn" type="submit">Change Password</button>
</form>

