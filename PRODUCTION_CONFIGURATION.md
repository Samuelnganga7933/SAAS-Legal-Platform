# 🔒 Production Environment Configuration Guide

**Purpose**: Configure Laravel application for secure production deployment  
**Updated**: March 22, 2026  
**Status**: Ready for deployment

---

## 1. Environment Variables (.env Configuration)

### Critical Security Settings

```env
# APP Configuration
APP_ENV=production          # MUST be 'production'
APP_DEBUG=false            # MUST be false (never expose debug info)
APP_KEY=base64:YOUR_KEY    # Generated via: php artisan key:generate

# URL Configuration
APP_URL=https://yourdomain.com  # MUST be HTTPS
ASSET_URL=https://yourdomain.com/

# Trust Proxies (for load balancers)
TRUSTED_PROXIES=*  # Or specific IPs: 10.0.0.0/8,172.16.0.0/12
```

### Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=production-db.company.com   # Remote host, not localhost
DB_PORT=3306
DB_DATABASE=leniumlegalops_prod     # Separate database from dev
DB_USERNAME=app_user                # Read-only user for security
DB_PASSWORD=STRONG_PASSWORD_HERE    # 32+ character random password
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci
DB_SSLMODE=require                  # Require SSL for remote DB
```

**Important**: Create a read-only database user for production:
```sql
-- MySQL
CREATE USER 'app_user'@'app-server-ip' IDENTIFIED BY 'STRONG_PASSWORD';
GRANT SELECT, INSERT, UPDATE, DELETE ON leniumlegalops_prod.* TO 'app_user'@'app-server-ip';
GRANT EXECUTE ON leniumlegalops_prod.* TO 'app_user'@'app-server-ip';
FLUSH PRIVILEGES;
```

### Caching Configuration

```env
# Use Redis for caching (not database)
CACHE_STORE=redis
CACHE_PREFIX=lenium_prod_

# Redis Connection
REDIS_CLIENT=phpredis
REDIS_HOST=redis.company.com
REDIS_PORT=6379
REDIS_PASSWORD=STRONG_REDIS_PASSWORD
```

### Session Configuration

```env
SESSION_DRIVER=database        # Or redis for better performance
SESSION_LIFETIME=1440          # 24 hours
SESSION_ENCRYPT=true           # Encrypt session data
SESSION_DOMAIN=.yourdomain.com # For subdomain support
SESSION_SAME_SITE=lax          # CSRF protection
SESSION_SECURE=true            # HTTPS only (production)
```

### Queue Configuration

```env
QUEUE_CONNECTION=database   # Or redis for async jobs
QUEUE_FAILED_TABLE=failed_jobs
```

### File Storage Configuration

```env
# Use S3 for production (not local filesystem)
FILESYSTEM_DISK=s3

# AWS S3 Credentials (use IAM user with S3-only permissions)
AWS_ACCESS_KEY_ID=AKIAIOSFODNN7EXAMPLE
AWS_SECRET_ACCESS_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=lenium-legal-documents
AWS_URL=https://lenium-legal-documents.s3.amazonaws.com

# S3 Configuration
AWS_VISIBILITY=private     # Documents are private by default
AWS_METADATA=[]            # Additional metadata
```

### Mail Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.your_sendgrid_api_key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Le Nium Legal"
```

### Logging Configuration

```env
LOG_CHANNEL=stack
LOG_LEVEL=info              # Not debug in production
LOG_STACK=single,sentry     # Sentry for error tracking

# Sentry Configuration
SENTRY_LARAVEL_DSN=https://xxxx@sentry.io/projectid
SENTRY_RELEASE=2.0.0
SENTRY_ENVIRONMENT=production
```

### Payment Processing

```env
STRIPE_PUBLIC_KEY=pk_live_xxxxx
STRIPE_SECRET_KEY=sk_live_xxxxx
STRIPE_WEBHOOK_SECRET=whsec_xxxxx
```

### Security & API Keys

```env
RECAPTCHA_SITE_KEY=your_site_key
RECAPTCHA_SECRET_KEY=your_secret_key

GOOGLE_CLIENT_ID=xxx.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=xxx
```

---

## 2. Configuration Files

### config/app.php

```php
'env' => env('APP_ENV', 'production'),
'debug' => env('APP_DEBUG', false),
'url' => env('APP_URL', 'https://yourdomain.com'),
'cipher' => env('CIPHER', 'AES-256-CBC'),
'timezone' => 'UTC', // Or your preferred timezone
```

### config/database.php

```php
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', 3306),
    'database' => env('DB_DATABASE', 'leniumlegalops_prod'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix' => '',
    'prefix_indexes' => true,
    'strict' => true,
    'engine' => 'InnoDB',
    'options' => extension_loaded('pdo_mysql') ? array_filter([
        PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ]) : [],
],
```

### config/session.php

```php
'driver' => env('SESSION_DRIVER', 'database'),
'lifetime' => env('SESSION_LIFETIME', 1440),
'expire_on_close' => false,
'encrypt' => env('SESSION_ENCRYPT', true),
'http_only' => true,      // JS can't access cookies
'same_site' => 'lax',     // CSRF protection
'secure' => env('APP_ENV') === 'production',  // HTTPS only
'domain' => env('SESSION_DOMAIN'),
```

### config/cache.php

```php
'default' => env('CACHE_STORE', 'redis'),
'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
    ],
],
'prefix' => env('CACHE_PREFIX', 'lenium_prod_'),
```

### config/queue.php

```php
'default' => env('QUEUE_CONNECTION', 'database'),
'connections' => [
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
    ],
],
```

### config/filesystems.php

```php
'default' => env('FILESYSTEM_DISK', 's3'),
'disks' => [
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
        'endpoint' => env('AWS_ENDPOINT'),
        'use_path_style_endpoint' => false,
        'visibility' => 'private',  // Documents are private
    ],
],
```

### config/mail.php

```php
'mailer' => env('MAIL_MAILER', 'smtp'),
'from' => [
    'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
    'name' => env('MAIL_FROM_NAME', 'Le Nium Legal'),
],
'mailers' => [
    'smtp' => [
        'transport' => 'smtp',
        'host' => env('MAIL_HOST'),
        'port' => env('MAIL_PORT'),
        'encryption' => env('MAIL_ENCRYPTION'),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'timeout' => null,
        'local_domain' => env('MAIL_EHLO_DOMAIN'),
    ],
],
```

---

## 3. Security Hardening

### PHP Configuration (php.ini)

```ini
; Production settings
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/log/php-errors.log

; Memory & Performance
memory_limit = 512M
max_execution_time = 60
max_input_time = 60
upload_max_filesize = 100M
post_max_size = 100M

; Security
open_basedir = /var/www/lenium-legal
disable_functions = exec,passthru,shell_exec,system,proc_open,popen,curl_exec,curl_multi_exec,parse_ini_file,show_source

; SSL/TLS
openssl.cnf = /etc/ssl/openssl.cnf

; Session Security
session.cookie_httponly = On
session.cookie_secure = On
session.cookie_samesite = Lax
session.use_only_cookies = On
session.gc_maxlifetime = 86400

; User Input
magic_quotes_gpc = Off
register_globals = Off
```

### PHP-FPM Configuration (php-fpm.conf)

```ini
[lenium-legal]
user = www-data
group = www-data
listen = /var/run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

; Process Manager
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 1000

; Timeouts
request_terminate_timeout = 300
```

---

## 4. Database Hardening

### MySQL Configuration (my.cnf)

```ini
[mysqld]
# Disable remote root access
skip-name-resolve
bind-address = localhost

# Performance
max_connections = 100
max_allowed_packet = 256M

# Replication & Backup
log_bin = /var/log/mysql/mysql-bin.log
expire_logs_days = 10

# Security
require_secure_transport = ON
ssl_ca=/etc/mysql/ca.pem
ssl_cert=/etc/mysql/server-cert.pem
ssl_key=/etc/mysql/server-key.pem
```

### Database Backups

```bash
# Daily backup script (/usr/local/bin/backup-db.sh)
#!/bin/bash

BACKUP_DIR="/backups/mysql"
DB_NAME="leniumlegalops_prod"
USERNAME="backup_user"
PASSWORD="BACKUP_PASSWORD"
DATE=$(date +%Y%m%d_%H%M%S)

mkdir -p $BACKUP_DIR

# Full backup
mysqldump -u $USERNAME -p$PASSWORD -h 127.0.0.1 \
    --all-databases \
    --single-transaction \
    --quick \
    --lock-tables=false | gzip > $BACKUP_DIR/full-$DATE.sql.gz

# Verify backup
if [ -f $BACKUP_DIR/full-$DATE.sql.gz ]; then
    echo "Backup successful: $BACKUP_DIR/full-$DATE.sql.gz"
    
    # Delete backups older than 30 days
    find $BACKUP_DIR -name "full-*.sql.gz" -mtime +30 -delete
else
    echo "Backup failed!" | mail -s "Database Backup Failed" admin@yourdomain.com
fi

# Add to crontab: 0 2 * * * /usr/local/bin/backup-db.sh
```

---

## 5. Web Server Hardening

### Nginx Tips

```bash
# Verify configuration before reload
sudo nginx -t

# Reload service
sudo systemctl reload nginx

# Enable service on boot
sudo systemctl enable nginx
```

### Apache Configuration

```bash
# Enable required modules
sudo a2enmod rewrite
sudo a2enmod headers
sudo a2enmod ssl
sudo a2enmod deflate
sudo a2enmod expires

# Disable unnecessary modules
sudo a2dismod autoindex
sudo a2dismod indexing

# Test configuration
sudo apache2ctl configtest

# Restart service
sudo systemctl restart apache2
sudo systemctl enable apache2
```

---

## 6. Secrets Management

### Storing API Keys Securely

**Option 1: Environment Variables** (Current approach)
- Easiest for small teams
- Store in `.env` (never commit to git)
- Use `git-crypt` to encrypt `.env` in version control

**Option 2: AWS Secrets Manager**
```php
// config/services.php
'stripe' => [
    'secret' => \Aws\SecretsManager\SecretsManagerClient::resolveSecret('prod/stripe-secret'),
],
```

**Option 3: HashiCorp Vault**
```php
// Use Vault client to fetch secrets at runtime
```

### .env Encryption with git-crypt

```bash
# Initialize encryption
cd /var/www/lenium-legal
git-crypt init

# Add .env to .gitattributes
echo ".env filter=git-crypt diff=git-crypt" >> .gitattributes

# Encrypt existing .env
git-crypt add-gpg-user YOUR_GPG_KEY_ID
git add .env .gitattributes
git commit -m "Encrypt sensitive files"
```

---

## 7. Monitoring & Alerting

### Application Monitoring

```php
// config/sentry.php
return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),
    'breadcrumbs' => true,
    'traces_sample_rate' => env('APP_ENV') === 'production' ? 0.1 : 1.0,
    'profiles_sample_rate' => env('APP_ENV') === 'production' ? 0.1 : 1.0,
    'environment' => env('SENTRY_ENVIRONMENT', 'production'),
    'release' => env('SENTRY_RELEASE', 'unknown'),
];
```

### Log Aggregation

```env
# Send logs to external service
LOG_STACK=single,sentry,papertrail

# Papertrail (free log management)
LOG_PAPERTRAIL_HOST=logs.papertrailapp.com
LOG_PAPERTRAIL_PORT=12345
```

### Health Checks

```bash
# Monitor via cron
* * * * * curl -f https://yourdomain.com/health || mail -s "Health Check Failed" admin@yourdomain.com
```

---

## 8. Pre-Deployment Checklist

- [ ] `.env` configured with production values
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Database migrations applied
- [ ] All caches cleared and rebuilt
- [ ] SSL certificate installed
- [ ] Backups automated
- [ ] Monitoring configured
- [ ] Security headers enabled
- [ ] Rate limiting configured
- [ ] File permissions set correctly
- [ ] Logging configured
- [ ] Error handling tested

---

## 9. Verification Commands

```bash
# SSH to production server
ssh deploy@yourdomain.com

# Check app environment
cd /var/www/lenium-legal
php artisan config:show | grep -E "APP_ENV|APP_DEBUG"

# Verify database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Check cache
>>> Cache::put('test', 'value', 3600)
>>> Cache::get('test')

# Verify file storage
>>> Storage::disk('s3')->put('test.txt', 'test')
>>> Storage::disk('s3')->exists('test.txt')

# Check application logs
tail -f storage/logs/laravel.log

# Monitor system resources
htop
df -h
free -h
```

---

**Production Configuration Complete** ✅

For security updates and best practices, monitor:
- Laravel Security Advisories: https://laravel.com/security
- OWASP Top 10: https://owasp.org/www-project-top-ten/
- CWE/SANS Top 25: https://cwe.mitre.org/top25/
