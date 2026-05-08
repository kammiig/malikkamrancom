SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) NOT NULL UNIQUE,
    `value` TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS navigation_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(120) NOT NULL,
    url VARCHAR(255) NOT NULL,
    position INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(80) NOT NULL UNIQUE,
    title VARCHAR(255) NULL,
    subtitle TEXT NULL,
    content MEDIUMTEXT NULL,
    image_path VARCHAR(255) NULL,
    file_path VARCHAR(255) NULL,
    extra_json JSON NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    icon_path VARCHAR(255) NULL,
    icon_label VARCHAR(40) NULL,
    position INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    category VARCHAR(190) NOT NULL,
    short_description TEXT NOT NULL,
    overview MEDIUMTEXT NULL,
    client_problem MEDIUMTEXT NULL,
    solution MEDIUMTEXT NULL,
    key_features MEDIUMTEXT NULL,
    results MEDIUMTEXT NULL,
    tech_stack TEXT NULL,
    image_path VARCHAR(255) NULL,
    live_url VARCHAR(255) NULL,
    seo_title VARCHAR(255) NULL,
    seo_description TEXT NULL,
    position INT NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 1,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS project_images (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    project_id BIGINT UNSIGNED NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    caption VARCHAR(255) NULL,
    position INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_project_images_project FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS skill_categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    position INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS skills (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(120) NOT NULL,
    position INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY unique_category_skill (category_id, title),
    CONSTRAINT fk_skills_category FOREIGN KEY (category_id) REFERENCES skill_categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS process_steps (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    step_number VARCHAR(40) NOT NULL,
    title VARCHAR(190) NOT NULL,
    description TEXT NOT NULL,
    position INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY unique_step_number (step_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS testimonials (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quote TEXT NOT NULL,
    client_name VARCHAR(190) NOT NULL,
    client_role VARCHAR(190) NULL,
    company VARCHAR(190) NULL,
    rating TINYINT UNSIGNED NOT NULL DEFAULT 5,
    image_path VARCHAR(255) NULL,
    position INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS enquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(190) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(80) NULL,
    project_type VARCHAR(120) NOT NULL,
    budget_range VARCHAR(80) NOT NULL,
    message MEDIUMTEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    ip_address VARCHAR(80) NULL,
    user_agent VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS seo_settings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    page_key VARCHAR(80) NOT NULL UNIQUE,
    path VARCHAR(255) NOT NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    meta_keywords TEXT NULL,
    og_title VARCHAR(255) NULL,
    og_description TEXT NULL,
    og_image VARCHAR(255) NULL,
    canonical_url VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS social_links (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(120) NOT NULL,
    url VARCHAR(255) NOT NULL,
    icon VARCHAR(80) NULL,
    position INT NOT NULL DEFAULT 0,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    UNIQUE KEY unique_social_label (label)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS uploaded_files (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    original_name VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    mime_type VARCHAR(120) NOT NULL,
    file_size INT UNSIGNED NOT NULL,
    context VARCHAR(120) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS content_pages (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(190) NOT NULL UNIQUE,
    title VARCHAR(190) NOT NULL,
    body MEDIUMTEXT NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO settings (`key`, `value`, created_at, updated_at) VALUES
('site_name', 'Muhammad Kamran Malik', NOW(), NOW()),
('logo_text', 'Muhammad Kamran Malik', NOW(), NOW()),
('header_cta_text', 'Hire Me', NOW(), NOW()),
('header_cta_link', '/contact', NOW(), NOW()),
('contact_email', 'hello@example.com', NOW(), NOW()),
('contact_phone', '+92 300 0000000', NOW(), NOW()),
('contact_location', 'Available for remote projects worldwide', NOW(), NOW()),
('footer_about', 'Professional web development for businesses that need fast, modern, conversion-focused websites and editable content systems.', NOW(), NOW()),
('copyright_text', '© 2026 Muhammad Kamran Malik. All Rights Reserved.', NOW(), NOW())
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = NOW();

INSERT INTO navigation_items (id, label, url, position, is_active, created_at, updated_at) VALUES
(1, 'Home', '/', 1, 1, NOW(), NOW()),
(2, 'About', '/about', 2, 1, NOW(), NOW()),
(3, 'Services', '/services', 3, 1, NOW(), NOW()),
(4, 'Projects', '/projects', 4, 1, NOW(), NOW()),
(5, 'Skills', '/#skills', 5, 1, NOW(), NOW()),
(6, 'Process', '/#process', 6, 1, NOW(), NOW()),
(7, 'Testimonials', '/#testimonials', 7, 1, NOW(), NOW()),
(8, 'Contact', '/contact', 8, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE label = VALUES(label), url = VALUES(url), position = VALUES(position), is_active = VALUES(is_active), updated_at = NOW();

INSERT INTO sections (section_key, title, subtitle, content, image_path, file_path, extra_json, created_at, updated_at) VALUES
('hero',
'I Build Fast, Modern & Conversion-Focused Websites for Businesses.',
'I’m Muhammad Kamran Malik, a Web Developer specialising in WordPress, Shopify, WHMCS, custom dashboards, Laravel/PHP projects, and business websites that are clean, responsive, and easy to manage.',
'',
NULL,
NULL,
JSON_OBJECT(
    'primary_button_text', 'View My Work',
    'primary_button_link', '/projects',
    'secondary_button_text', 'Let’s Work Together',
    'secondary_button_link', '/contact',
    'stats', JSON_ARRAY(
        JSON_OBJECT('number', '8+', 'label', 'Years Experience'),
        JSON_OBJECT('number', '100+', 'label', 'Websites'),
        JSON_OBJECT('number', 'WordPress', 'label', 'Shopify / WHMCS Specialist')
    )
),
NOW(),
NOW()),
('about',
'Professional Web Developer with 8+ years of experience.',
'Websites, eCommerce stores, hosting platforms, dashboards, and custom business solutions.',
'I’m a professional Web Developer with over 8 years of experience creating websites, eCommerce stores, hosting platforms, dashboards, and custom business solutions. My focus is simple: build websites that look professional, load fast, work smoothly on all devices, and help businesses get more enquiries, sales, and trust online.',
NULL,
NULL,
JSON_OBJECT('experience_details', JSON_ARRAY('WordPress', 'Shopify', 'WHMCS', 'HTML/CSS/JavaScript', 'PHP/Laravel', 'MySQL', 'cPanel', 'GitHub', 'Cloudflare', 'Custom dashboards')),
NOW(),
NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), subtitle = VALUES(subtitle), content = VALUES(content), extra_json = VALUES(extra_json), updated_at = NOW();

INSERT INTO services (title, slug, description, icon_label, position, is_active, created_at, updated_at) VALUES
('Business Website Development', 'business-website-development', 'Clean, responsive websites for companies, agencies, and local businesses.', 'BW', 1, 1, NOW(), NOW()),
('WordPress Development', 'wordpress-development', 'Custom WordPress websites, Elementor Pro builds, theme customisation, and plugin setup.', 'WP', 2, 1, NOW(), NOW()),
('Shopify Store Development', 'shopify-store-development', 'Professional eCommerce stores with product pages, checkout setup, and payment integration.', 'SP', 3, 1, NOW(), NOW()),
('WHMCS / Hosting Website Development', 'whmcs-hosting-website-development', 'Hosting company websites connected with WHMCS client areas, domain ordering, and hosting plans.', 'WH', 4, 1, NOW(), NOW()),
('Custom Web Apps & Dashboards', 'custom-web-apps-dashboards', 'Customer portals, booking systems, ledgers, admin dashboards, and business management tools.', 'DB', 5, 1, NOW(), NOW()),
('Website Maintenance & Optimisation', 'website-maintenance-optimisation', 'Speed optimisation, security updates, backups, bug fixing, and ongoing support.', 'MO', 6, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), icon_label = VALUES(icon_label), position = VALUES(position), is_active = VALUES(is_active), updated_at = NOW();

INSERT INTO projects (title, slug, category, short_description, overview, client_problem, solution, key_features, results, tech_stack, live_url, position, is_featured, is_active, created_at, updated_at) VALUES
('Planetic Solutions Hosting Website', 'planetic-solutions-hosting-website', 'Hosting / WHMCS Website',
'A modern hosting company website integrated with WHMCS client area, hosting packages, domain/hosting ordering, and a website development service package.',
'A complete hosting company website designed to present hosting packages, domain services, WHMCS client access, and website development services in a clear business-focused structure.',
'The business needed a professional hosting website that made plans easy to compare and connected visitors with WHMCS ordering and client-area workflows.',
'I structured the website around hosting plans, domain ordering, trust sections, service packages, and clear CTAs leading into the WHMCS client area.',
'WHMCS client-area integration
Hosting package sections
Domain and hosting ordering flow
Website development service package
cPanel and Cloudflare-ready deployment
Responsive WordPress layout',
'The website gives the hosting brand a stronger online presence and a cleaner path from visitor interest to package enquiry or order.',
'WordPress, WHMCS, PHP, cPanel, GitHub, Cloudflare',
'', 1, 1, 1, NOW(), NOW()),
('FTC Installment Management System', 'ftc-installment-management-system', 'Laravel Dashboard / Business Web App',
'A custom business management portal for customer ledgers, installments, payment records, receipts, guarantor details, reports, and PDF/WhatsApp receipt features.',
'A custom Laravel dashboard created to manage customer installment records, ledgers, payments, receipts, guarantor details, reports, and staff workflows.',
'The company needed to replace manual record keeping with a searchable, secure, and structured system for installment management.',
'I built a database-driven Laravel portal with customer profiles, ledger tracking, installment records, receipt generation, reporting, and admin management tools.',
'Customer ledgers
Installment and payment records
Receipt generation
PDF and WhatsApp receipt workflow
Guarantor details
Reports and admin dashboard',
'The system reduced manual tracking work and gave the business a clearer view of customers, payments, balances, and reports.',
'Laravel, MySQL, PHP, Bootstrap, cPanel, GitHub',
'', 2, 1, 1, NOW(), NOW()),
('Tryn HQ Fitness Website', 'tryn-hq-fitness-website', 'Fitness / Booking Website',
'A modern fitness website with class details, free class booking form, gallery, SEO sections, and conversion-focused content.',
'A conversion-focused fitness website built to present classes, showcase the facility, capture free class bookings, and support local SEO.',
'The business needed a polished website that could turn visitors into trial bookings while explaining class options clearly.',
'I created a responsive WordPress layout with strong class sections, booking forms, gallery content, SEO areas, and direct calls to action.',
'Class detail sections
Free class booking form
Gallery
SEO content blocks
Mobile-first layout
Conversion-focused CTAs',
'The website improved the clarity of the offer and created a smoother path for visitors to book a free class.',
'WordPress, Elementor, Forms, SEO, Responsive Design',
'', 3, 1, 1, NOW(), NOW()),
('Kban Logistics', 'kban-logistics', 'Business Website',
'A logistics website focused on same-day delivery, man with a van, removals, pallet delivery, and service enquiry generation.',
'A business website for a logistics provider offering delivery, removals, pallet transport, and service enquiry options.',
'The business needed a credible service website that explained multiple logistics services and generated enquiries from local and commercial customers.',
'I built a clean service-led website with dedicated sections for delivery types, trust signals, enquiry forms, and SEO-friendly content.',
'Same-day delivery service pages
Man with a van content
Removals and pallet delivery sections
Service enquiry forms
SEO-friendly page structure',
'The website gives visitors a clear view of services and makes it easier to request a quote or discuss delivery needs.',
'WordPress, Elementor, SEO, Forms',
'', 4, 1, 1, NOW(), NOW()),
('AM Wholesale Ltd', 'am-wholesale-ltd', 'Wholesale / eCommerce Website',
'A wholesale business website for children’s clothing, toys, men’s jeans, shirts, quotation forms, product categories, and company pages.',
'A wholesale website created to present product categories, company information, quote requests, and future eCommerce growth options.',
'The business needed a professional online presence that could show wholesale product ranges and capture quotation enquiries.',
'I structured the website around product categories, company pages, quotation forms, SEO sections, and clean WooCommerce-ready content.',
'Wholesale product categories
Quotation forms
Company profile pages
WooCommerce-ready structure
SEO content
Responsive design',
'The site presents the wholesale catalogue more professionally and supports quote generation through clear product and contact flows.',
'WordPress, WooCommerce, SEO, Forms',
'', 5, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), category = VALUES(category), short_description = VALUES(short_description), overview = VALUES(overview), client_problem = VALUES(client_problem), solution = VALUES(solution), key_features = VALUES(key_features), results = VALUES(results), tech_stack = VALUES(tech_stack), position = VALUES(position), is_featured = VALUES(is_featured), is_active = VALUES(is_active), updated_at = NOW();

INSERT INTO skill_categories (id, title, position, created_at, updated_at) VALUES
(1, 'Front-End', 1, NOW(), NOW()),
(2, 'CMS / eCommerce', 2, NOW(), NOW()),
(3, 'Backend / Tools', 3, NOW(), NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), position = VALUES(position), updated_at = NOW();

INSERT INTO skills (category_id, title, position, created_at, updated_at) VALUES
(1, 'HTML5', 1, NOW(), NOW()),
(1, 'CSS3', 2, NOW(), NOW()),
(1, 'JavaScript', 3, NOW(), NOW()),
(1, 'Responsive Design', 4, NOW(), NOW()),
(1, 'UI Layouts', 5, NOW(), NOW()),
(1, 'Speed Optimisation', 6, NOW(), NOW()),
(2, 'WordPress', 1, NOW(), NOW()),
(2, 'Shopify', 2, NOW(), NOW()),
(2, 'WooCommerce', 3, NOW(), NOW()),
(2, 'Elementor Pro', 4, NOW(), NOW()),
(2, 'WHMCS', 5, NOW(), NOW()),
(2, 'Theme Customisation', 6, NOW(), NOW()),
(3, 'PHP', 1, NOW(), NOW()),
(3, 'Laravel', 2, NOW(), NOW()),
(3, 'MySQL', 3, NOW(), NOW()),
(3, 'GitHub', 4, NOW(), NOW()),
(3, 'cPanel', 5, NOW(), NOW()),
(3, 'Cloudflare', 6, NOW(), NOW())
ON DUPLICATE KEY UPDATE position = VALUES(position), updated_at = NOW();

INSERT INTO process_steps (step_number, title, description, position, created_at, updated_at) VALUES
('Step 01', 'Discovery', 'I understand the business, audience, and project goals.', 1, NOW(), NOW()),
('Step 02', 'Planning', 'I plan sitemap, features, content structure, and technical setup.', 2, NOW(), NOW()),
('Step 03', 'Design', 'I create a clean and professional layout focused on trust and conversion.', 3, NOW(), NOW()),
('Step 04', 'Development', 'I build the website using responsive, scalable, and clean code.', 4, NOW(), NOW()),
('Step 05', 'Testing', 'I test mobile responsiveness, speed, forms, links, and browser compatibility.', 5, NOW(), NOW()),
('Step 06', 'Launch & Support', 'I deploy the website and provide support for future updates.', 6, NOW(), NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), position = VALUES(position), updated_at = NOW();

INSERT INTO testimonials (id, quote, client_name, client_role, company, rating, position, is_active, created_at, updated_at) VALUES
(1, 'Kamran created a professional website for our business with a clean design, smooth user experience, and all requested features. Communication was excellent throughout the project.', 'Business Client', 'Owner', 'Website Project', 5, 1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE quote = VALUES(quote), client_name = VALUES(client_name), client_role = VALUES(client_role), company = VALUES(company), rating = VALUES(rating), position = VALUES(position), is_active = VALUES(is_active), updated_at = NOW();

INSERT INTO social_links (label, url, icon, position, is_active, created_at, updated_at) VALUES
('GitHub', 'https://github.com/', 'GitHub', 1, 1, NOW(), NOW()),
('LinkedIn', 'https://www.linkedin.com/', 'LinkedIn', 2, 1, NOW(), NOW()),
('WhatsApp', 'https://wa.me/', 'WhatsApp', 3, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE url = VALUES(url), icon = VALUES(icon), position = VALUES(position), is_active = VALUES(is_active), updated_at = NOW();

INSERT INTO seo_settings (page_key, path, meta_title, meta_description, meta_keywords, og_title, og_description, canonical_url, created_at, updated_at) VALUES
('home', '/', 'Muhammad Kamran Malik | Web Developer for Business Websites, WordPress, Shopify, WHMCS & Laravel', 'Muhammad Kamran Malik is a professional Web Developer building fast, modern, responsive, and conversion-focused websites, Shopify stores, WHMCS sites, and custom dashboards.', 'web developer, WordPress developer, Shopify developer, WHMCS, Laravel developer, PHP developer', 'Muhammad Kamran Malik | Web Developer', 'Fast, modern, editable websites and custom web solutions for businesses.', '', NOW(), NOW()),
('about', '/about', 'About Muhammad Kamran Malik | Professional Web Developer', 'Learn about Muhammad Kamran Malik, a Web Developer with 8+ years of experience in WordPress, Shopify, WHMCS, PHP, Laravel, MySQL, and business websites.', 'about web developer, Muhammad Kamran Malik', 'About Muhammad Kamran Malik', 'Professional Web Developer with 8+ years of experience.', '', NOW(), NOW()),
('services', '/services', 'Web Development Services | WordPress, Shopify, WHMCS, Laravel & Maintenance', 'Explore professional web development services including business websites, WordPress, Shopify stores, WHMCS hosting websites, custom dashboards, and optimisation.', 'web development services, WordPress services, Shopify store, WHMCS hosting website', 'Web Development Services', 'Business websites, stores, dashboards, WHMCS websites, and maintenance services.', '', NOW(), NOW()),
('projects', '/projects', 'Portfolio Projects | Muhammad Kamran Malik', 'View featured portfolio projects including hosting websites, Laravel dashboards, fitness booking sites, logistics websites, and wholesale eCommerce websites.', 'portfolio projects, web development case studies', 'Portfolio Projects', 'Selected web development projects and case studies.', '', NOW(), NOW()),
('project', '/projects/project-slug', 'Project Case Study | Muhammad Kamran Malik', 'Read a detailed project case study covering the client problem, solution, features, tech stack, and results.', 'case study, web development project', 'Project Case Study', 'Detailed web development case study.', '', NOW(), NOW()),
('contact', '/contact', 'Contact Muhammad Kamran Malik | Hire Web Developer', 'Contact Muhammad Kamran Malik for business websites, WordPress, Shopify, WHMCS, custom dashboards, Laravel projects, and website maintenance.', 'hire web developer, contact WordPress developer, PHP developer', 'Contact Muhammad Kamran Malik', 'Discuss your next website, store, dashboard, or hosting platform.', '', NOW(), NOW()),
('privacy', '/privacy-policy', 'Privacy Policy | Muhammad Kamran Malik', 'Read the privacy policy for Muhammad Kamran Malik portfolio website.', 'privacy policy', 'Privacy Policy', 'Privacy policy for this website.', '', NOW(), NOW()),
('terms', '/terms-conditions', 'Terms & Conditions | Muhammad Kamran Malik', 'Read the terms and conditions for Muhammad Kamran Malik portfolio website.', 'terms and conditions', 'Terms & Conditions', 'Terms and conditions for this website.', '', NOW(), NOW())
ON DUPLICATE KEY UPDATE meta_title = VALUES(meta_title), meta_description = VALUES(meta_description), meta_keywords = VALUES(meta_keywords), og_title = VALUES(og_title), og_description = VALUES(og_description), canonical_url = VALUES(canonical_url), updated_at = NOW();

INSERT INTO content_pages (slug, title, body, is_active, created_at, updated_at) VALUES
('privacy-policy', 'Privacy Policy', 'This privacy policy explains how enquiries and website data are handled.

When you submit the contact form, your name, email address, phone number, project type, budget range, and message are stored so the website owner can reply to your enquiry.

Your information is not sold or shared for marketing purposes. Basic technical information such as IP address and browser user agent may be stored to help prevent spam and improve website security.

You can request deletion of your enquiry information by contacting the website owner through the published contact email.', 1, NOW(), NOW()),
('terms-conditions', 'Terms & Conditions', 'By using this website, you agree to use it lawfully and not attempt to access private admin areas, upload unsafe files, or misuse the contact form.

Portfolio content, service descriptions, and project summaries are provided for general information. Project timelines, pricing, and deliverables are confirmed separately for each client.

External links may lead to third-party websites. The website owner is not responsible for the content or privacy practices of external websites.', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE title = VALUES(title), body = VALUES(body), is_active = VALUES(is_active), updated_at = NOW();
