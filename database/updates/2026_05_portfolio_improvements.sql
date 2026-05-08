SET NAMES utf8mb4;

ALTER TABLE services
    ADD COLUMN icon_alt VARCHAR(255) NULL AFTER icon_label,
    ADD COLUMN icon_title VARCHAR(255) NULL AFTER icon_alt;

ALTER TABLE projects
    ADD COLUMN image_alt VARCHAR(255) NULL AFTER image_path,
    ADD COLUMN image_title VARCHAR(255) NULL AFTER image_alt;

ALTER TABLE project_images
    ADD COLUMN alt_text VARCHAR(255) NULL AFTER image_path,
    ADD COLUMN title_text VARCHAR(255) NULL AFTER alt_text;

ALTER TABLE testimonials
    ADD COLUMN image_alt VARCHAR(255) NULL AFTER image_path,
    ADD COLUMN image_title VARCHAR(255) NULL AFTER image_alt;

ALTER TABLE seo_settings
    ADD COLUMN og_image_alt VARCHAR(255) NULL AFTER og_image,
    ADD COLUMN og_image_title VARCHAR(255) NULL AFTER og_image_alt;

ALTER TABLE uploaded_files
    ADD COLUMN alt_text VARCHAR(255) NULL AFTER context,
    ADD COLUMN title_text VARCHAR(255) NULL AFTER alt_text;

INSERT INTO settings (`key`, `value`, created_at, updated_at) VALUES
('logo_path', '', NOW(), NOW()),
('logo_alt', 'Muhammad Kamran Malik logo', NOW(), NOW()),
('logo_title', 'Muhammad Kamran Malik', NOW(), NOW()),
('favicon_path', '', NOW(), NOW()),
('privacy_link', '/privacy-policy', NOW(), NOW()),
('terms_link', '/terms-conditions', NOW(), NOW())
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = NOW();

UPDATE services SET icon_label = 'monitor', icon_alt = 'Business website development icon', icon_title = title WHERE slug = 'business-website-development';
UPDATE services SET icon_label = 'code', icon_alt = 'WordPress development icon', icon_title = title WHERE slug = 'wordpress-development';
UPDATE services SET icon_label = 'shopping-bag', icon_alt = 'Shopify store development icon', icon_title = title WHERE slug = 'shopify-store-development';
UPDATE services SET icon_label = 'server', icon_alt = 'WHMCS hosting website icon', icon_title = title WHERE slug = 'whmcs-hosting-website-development';
UPDATE services SET icon_label = 'dashboard', icon_alt = 'Custom dashboard icon', icon_title = title WHERE slug = 'custom-web-apps-dashboards';
UPDATE services SET icon_label = 'wrench', icon_alt = 'Website maintenance icon', icon_title = title WHERE slug = 'website-maintenance-optimisation';

UPDATE social_links SET icon = LOWER(label) WHERE LOWER(label) IN ('github', 'linkedin', 'whatsapp', 'facebook', 'instagram');

UPDATE sections
SET extra_json = JSON_OBJECT(
    'primary_button_text', COALESCE(JSON_UNQUOTE(JSON_EXTRACT(extra_json, '$.primary_button_text')), 'View My Work'),
    'primary_button_link', COALESCE(JSON_UNQUOTE(JSON_EXTRACT(extra_json, '$.primary_button_link')), '/projects'),
    'secondary_button_text', COALESCE(JSON_UNQUOTE(JSON_EXTRACT(extra_json, '$.secondary_button_text')), 'Let’s Work Together'),
    'secondary_button_link', COALESCE(JSON_UNQUOTE(JSON_EXTRACT(extra_json, '$.secondary_button_link')), '/contact'),
    'enable_image', false,
    'image_alt', 'Muhammad Kamran Malik web developer',
    'image_title', 'Muhammad Kamran Malik',
    'stats', JSON_ARRAY(
        JSON_OBJECT('number', '8+', 'label', 'Years Experience'),
        JSON_OBJECT('number', '8K+', 'label', 'Successful Business Websites Created'),
        JSON_OBJECT('number', 'WordPress', 'label', 'Shopify / WHMCS Specialist')
    )
)
WHERE section_key = 'hero';

UPDATE sections
SET extra_json = JSON_SET(
    COALESCE(extra_json, JSON_OBJECT()),
    '$.image_style', 'black-white',
    '$.image_alt', 'Muhammad Kamran Malik profile image',
    '$.image_title', 'Muhammad Kamran Malik'
)
WHERE section_key = 'about';
