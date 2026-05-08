# Muhammad Kamran Malik Portfolio

Dynamic personal portfolio website with a public website, secure admin dashboard, MySQL database, editable content, image uploads, SEO controls, and saved contact enquiries.

## Stack

- PHP 8.1+
- MySQL 5.7+ or MariaDB 10.3+
- PDO
- Modern HTML/CSS/JavaScript
- No Composer or Node build step required

## Folder Structure

```text
app/                 Controllers, core helpers, and PHP views
config/              Bootstrap and environment loading
database/schema.sql  Database tables and starter content
public/              Web root, assets, uploads, and index.php
tools/               CLI helper scripts
storage/             Logs or private runtime files
.env.example         Environment template
```

## Local Installation

1. Copy the environment file:

```bash
cp .env.example .env
```

2. Edit `.env` and set `APP_URL`, database name, database user, database password, and email sender values.

3. Create a MySQL database and import:

```bash
mysql -u your_database_user -p your_database_name < database/schema.sql
```

For an existing installation created before the logo/hero/media improvements, run the update SQL once:

```bash
mysql -u your_database_user -p your_database_name < database/updates/2026_05_portfolio_improvements.sql
```

4. Create the admin user:

```bash
php tools/create_admin.php "Admin Name" admin@example.com "StrongPassword123"
```

5. Run locally:

```bash
php -S localhost:8000 -t public
```

Open `http://localhost:8000` for the website and `http://localhost:8000/admin` for the dashboard.

## Admin Dashboard

Admin URL:

```text
/admin
```

The dashboard lets you manage:

- Header logo image, favicon, logo alt/title text, navigation labels, CTA, footer, social links, and contact details
- Hero text, buttons, stats, image upload, image remove, image enable/disable, and image alt/title text
- About text, profile image, image style, image alt/title text, experience details, and resume file
- Services, predefined service icons, optional custom icon images, icon alt/title text, and ordering
- Projects, case studies, screenshots, live links, featured status, and ordering
- Skills and skill categories
- Process steps
- Testimonials
- Contact enquiries, read/unread status, and deletion
- SEO meta titles, descriptions, keywords, Open Graph data, canonical URLs, and OG images
- Privacy Policy and Terms & Conditions pages
- Uploaded media metadata, replacement, and deletion
- Admin password

## Contact Form

The contact form:

- Validates required fields
- Uses CSRF protection
- Uses a simple math captcha
- Saves enquiries in the database
- Sends an email notification to the `contact_email` setting

Make sure your cPanel account can send PHP `mail()` messages. If your host blocks `mail()`, connect SMTP by replacing `app/Core/Mailer.php` with your host’s preferred SMTP setup.

## Uploads

Uploads are stored in:

```text
public/uploads/YYYY/MM/
```

Allowed image types:

- JPG
- PNG
- WebP
- GIF

Allowed resume file types:

- PDF
- DOC
- DOCX

Set `public/uploads` permissions to `755` or `775` depending on your host.

## cPanel Deployment

Recommended deployment:

1. Upload the project to your hosting account.
2. Set the domain document root to the `public` folder.
3. Copy `.env.example` to `.env` and update database credentials.
4. Create a MySQL database and user in cPanel.
5. Import `database/schema.sql` through phpMyAdmin.
   Existing installations should also import `database/updates/2026_05_portfolio_improvements.sql` once.
6. Create the admin user from cPanel Terminal:

```bash
php tools/create_admin.php "Admin Name" admin@example.com "StrongPassword123"
```

7. Ensure `public/uploads` is writable.
8. Visit `/admin` and start editing content.

If your cPanel plan cannot set the document root to `public`, upload the full project as-is. The root `.htaccess` forwards requests into `public`, but the dedicated `public` document root is cleaner and more secure.

## GitHub Updates

After pushing the project to GitHub, a typical update flow on cPanel Terminal is:

```bash
git pull origin main
```

Then import any new SQL changes manually if the database schema changes in the future. For this update, existing sites should run `database/updates/2026_05_portfolio_improvements.sql` once. Do not commit `.env` or uploaded private files.

## Security Notes

- Admin passwords are hashed with PHP `password_hash()`.
- Database credentials stay in `.env`, which is ignored by Git.
- CSRF protection is enabled for admin and contact forms.
- Uploaded files are MIME-checked and size-limited.
- Main content is escaped before output.
- Admin credentials are not included in the source code or SQL seed.
