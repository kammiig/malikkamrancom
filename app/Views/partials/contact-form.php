<?php
$projectTypes = [
    'Business Website',
    'WordPress Website',
    'Shopify Store',
    'WHMCS / Hosting Website',
    'Custom Web App',
    'Website Maintenance',
    'Other',
];
$budgets = ['Under £200', '£200 – £500', '£500 – £1,000', '£1,000+', 'Not sure yet'];
?>
<form class="contact-form" action="<?= url('/contact') ?>" method="post" id="contact-form">
    <?= csrf_field() ?>
    <?php if ($message = flash('success')): ?>
        <div class="notice success"><?= e($message) ?></div>
    <?php endif; ?>
    <?php if ($message = flash('error')): ?>
        <div class="notice error"><?= e($message) ?></div>
    <?php endif; ?>

    <div class="form-grid two">
        <label>Full Name <input type="text" name="full_name" value="<?= e(old('full_name')) ?>" required></label>
        <label>Email Address <input type="email" name="email" value="<?= e(old('email')) ?>" required></label>
        <label>Phone Number <input type="text" name="phone" value="<?= e(old('phone')) ?>"></label>
        <label>Project Type
            <select name="project_type" required>
                <option value="">Select project type</option>
                <?php foreach ($projectTypes as $type): ?>
                    <option value="<?= e($type) ?>" <?= selected(old('project_type'), $type) ?>><?= e($type) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Budget Range
            <select name="budget_range" required>
                <option value="">Select budget</option>
                <?php foreach ($budgets as $budget): ?>
                    <option value="<?= e($budget) ?>" <?= selected(old('budget_range'), $budget) ?>><?= e($budget) ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Spam Protection <input type="text" name="captcha" placeholder="<?= e($captchaQuestion ?? '') ?>" required></label>
    </div>
    <label>Message <textarea name="message" rows="6" required><?= e(old('message')) ?></textarea></label>
    <button class="btn" type="submit">Send Enquiry</button>
</form>

