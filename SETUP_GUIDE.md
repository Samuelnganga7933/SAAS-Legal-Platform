# Le Nium Advisors - Laravel Setup & Management Guide

## 📋 Quick Start

### Prerequisites
- XAMPP installed with MySQL running
- PHP 8.2+ (via XAMPP)
- Composer installed
- Database: `leniumlegalops`

### Starting the Application

```bash
cd "c:\xampp\htdocs\Le Nium Advisors website\nium-advisors-laravel"
php artisan serve --host 127.0.0.1 --port 8001
```

**Access at:** http://127.0.0.1:8001

---

## 📊 Database Information

### Database Name
`leniumlegalops`

### Tables Created
1. **users** - User accounts (from default Laravel migration)
   - id, name, email, email_verified_at, password, remember_token, created_at, updated_at

2. **contact_submissions** - Contact form submissions
   - id, name (encrypted), email (encrypted), country, service, message (encrypted), consent, status, ip_address, created_at, updated_at, deleted_at

3. **consultations** - Consultation booking requests
   - id, email (encrypted), full_name (encrypted), phone, preferred_date_time, description (encrypted), status, zoom_link (encrypted), ip_address, created_at, updated_at, deleted_at

4. **sessions** - Encrypted session storage
5. **failed_jobs** - Job queue failures
6. **cache** - Cache table
7. **jobs** - Queue jobs

### Accessing MySQL

**Via Command Line:**
```bash
C:\xampp\mysql\bin\mysql.exe -u root -p leniumlegalops
```
(Press Enter when prompted for password - default is blank)

**Via phpMyAdmin:**
1. Start XAMPP Apache
2. Go to http://localhost/phpmyadmin
3. Click on `leniumlegalops` database

---

## 📁 Project Structure

```
nium-advisors-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ContactController.php  (form submissions)
│   │   │   └── PageController.php     (page routing)
│   │   └── Middleware/
│   ├── Models/
│   │   ├── ContactSubmission.php
│   │   ├── Consultation.php
│   │   └── User.php
├── database/
│   ├── migrations/          (database schema)
│   └── seeders/
├── resources/
│   ├── css/
│   │   └── app.css          (Tailwind CSS)
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php (master layout)
│       ├── partials/        (header, footer, etc)
│       ├── home.blade.php
│       └── disclaimer.blade.php
├── routes/
│   └── web.php              (URL routes)
├── public/
│   ├── index.php            (entry point)
│   ├── css/                 (compiled Tailwind)
│   └── js/                  (compiled JavaScript)
├── .env                     (configuration - KEEP SECRET)
├── config/                  (application configuration)
└── storage/
    ├── logs/                (application logs)
    └── framework/
```

---

## 🌐 Available Routes

| Method | Route | Purpose |
|--------|-------|---------|
| GET | `/` | Home page with all sections |
| GET | `/disclaimer` | Full legal disclaimer |
| POST | `/contact` | Contact form submission |
| POST | `/create-account` | User account creation |
| POST | `/signup` | Consultation booking |
| GET | `/health` | Health check (returns `{status: ok}`) |

---

## 🔧 Common Commands

### View Logs
```bash
php artisan logs:view
```

### Tinker (Interactive PHP Shell)
```bash
php artisan tinker
```

Inside tinker:
```php
# View all contact submissions
App\Models\ContactSubmission::all();

# Get specific submission
App\Models\ContactSubmission::find(1);

# View consultation requests
App\Models\Consultation::all();

# Search by email
App\Models\ContactSubmission::where('email', 'user@example.com')->get();

# Soft delete a record
$submission = App\Models\ContactSubmission::find(1);
$submission->delete();

# View deleted records
App\Models\ContactSubmission::onlyTrashed()->get();

# Restore deleted record
$submission = App\Models\ContactSubmission::onlyTrashed()->find(1);
$submission->restore();

# Permanently delete
$submission = App\Models\ContactSubmission::onlyTrashed()->find(1);
$submission->forceDelete();
```

### Database Commands
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migrate (drops & recreates)
php artisan migrate:fresh

# Create new migration
php artisan make:migration create_table_name

# Create model
php artisan make:model ModelName

# Create controller
php artisan make:controller ControllerName
```

### Cache/Config
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Rebuild cache
php artisan config:cache
```

---

## 🔐 Environment Variables (.env)

**Critical Variables:**
```env
APP_KEY=base64:xxxxx                    # Encryption key (auto-generated)
APP_ENV=local|production
APP_DEBUG=false                         # MUST be false in production
APP_URL=http://127.0.0.1:8001

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leniumlegalops
DB_USERNAME=root
DB_PASSWORD=

# Session Encryption
SESSION_ENCRYPT=true
SESSION_DRIVER=database

# Mail (if sending emails)
MAIL_MAILER=log|smtp
MAIL_FROM_ADDRESS=hello@example.com
```

---

## 📊 Viewing Form Submissions

### In phpMyAdmin
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Select `leniumlegalops` database
3. Click `contact_submissions` or `consultations` table
4. View submitted data

**Note:** Encrypted fields display as encrypted text - not readable without application decryption

### Via Artisan Command
```bash
php artisan tinker

# View all recent submissions
App\Models\ContactSubmission::latest()->first();

# Example output (note: encrypted fields are automatically decrypted):
=> App\Models\ContactSubmission {
     id: 1,
     name: "John Doe",           # Automatically decrypted when accessed
     email: "john@example.com",
     message: "Hello, I have a question about compliance...",
     created_at: "2026-02-10 20:30:45",
   }
```

---

## 🚀 Production Deployment

### 1. Server Setup
- Copy entire `nium-advisors-laravel` folder to production server
- Ensure PHP 8.2+, MySQL 8.0+, Composer installed

### 2. Configuration
```bash
# On production server
cd nium-advisors-laravel

# Copy environment template
cp .env.example .env

# Generate key
php artisan key:generate

# Update .env
# - DB credentials
# - APP_URL (production domain)
# - MAIL settings (for notifications)
# - APP_DEBUG=false
```

### 3. Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install --production
npm run build
```

### 4. Build Assets
```bash
npm run build    # Production build
```

### 5. Database
```bash
php artisan migrate --force
```

### 6. Optimize
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7. Web Server Config
**Apache (.htaccess in public folder):**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php?/$1 [L]
</IfModule>
```

**Nginx (server block):**
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 8. Permissions
```bash
chmod 755 storage
chmod 755 bootstrap/cache
chmod 600 .env
```

### 9. SSL Certificate
- Obtain SSL cert (Let's Encrypt free option)
- Configure in web server
- Update APP_URL to https://

### 10. Backups
- Set up automated database backups (daily minimum)
- Backup storage directory weekly
- Test recovery process

---

## 🐛 Troubleshooting

### "Base table or view not found" Error
```bash
php artisan migrate --force
```

### "SQLSTATE connection refused"
- Ensure MySQL is running in XAMPP
- Check .env DB credentials

### "Whoops! There was an error"
- Check `storage/logs/laravel.log`
- Set `APP_DEBUG=true` temporarily (development only)

### Rate limiting triggered
- Wait for timeout period (1 hour for contact, 1 day for others)
- Or clear rate limiting: use different IP/clear session

### Forms not sending
- Check validation in `app/Http/Controllers/ContactController.php`
- Review browser console for JS errors
- Check `storage/logs/laravel.log` for errors

---

## 📧 Email Configuration (Optional)

### To send actual emails
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io      # or your email provider
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@leniumadvisors.com
MAIL_FROM_NAME="Le Nium Advisors"
```

### Create notification class
```bash
php artisan make:notification ContactFormSubmitted
```

---

## 🔍 Monitoring & Logs

### View Real-time Logs
```bash
php artisan logs:view
```

### Monitor Activity
```bash
# Check failed logins, rate limiting, errors
tail -f storage/logs/laravel.log
```

### Database Queries Log
Add to `.env`:
```env
DB_QUERY_LOG=true
```

Then in `.env` also:
```env
APP_DEBUG=true  # (development only)
LOG_LEVEL=debug
```

---

## 📞 Support

For issues or questions:
- Email: leniumtradinggroup@outlook.com
- WhatsApp: +254 104 921 009
- Review SECURITY.md for security concerns

---

**Last Updated:** February 10, 2026
