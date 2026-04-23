# 🚀 Production Deployment Guide - Le Nium Legal

**Status**: Ready for production deployment  
**Version**: 2.0.0  
**Last Updated**: March 22, 2026

---

## Pre-Deployment Checklist

### 1. Environment & Dependencies
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `npm install && npm run build` (if using Vite assets)
- [ ] Verify PHP version: 8.2+ (check `php -v`)
- [ ] Verify MySQL 8.0+ (check `mysql --version`)
- [ ] Verify composer version 2.0+ (check `composer --version`)
- [ ] Check all required PHP extensions: `php -m | grep -E "(pdo_mysql|redis|openssl|mbstring)"`

### 2. Application Setup
- [ ] Copy `.env.production` to `.env` and update credentials
- [ ] Run `php artisan key:generate` (generates APP_KEY if missing)
- [ ] Run `php artisan migrate --force` (apply database migrations)
- [ ] Run `composer dump-autoload -o` (optimize autoloader)
- [ ] Run `php artisan config:cache` (cache configuration)
- [ ] Run `php artisan route:cache` (cache routes)
- [ ] Run `php artisan view:cache` (cache views)
- [ ] Run `mkdir -p storage/app/documents` (create document storage)
- [ ] Run `php artisan storage:link` (create public disk symlink)

### 3. Security
- [ ] Verify `.env` is NOT publicly accessible (check `.gitignore`)
- [ ] Verify `APP_DEBUG=false` in production
- [ ] Verify `APP_ENV=production` in production
- [ ] Set strong passwords for DB, Redis, etc.
- [ ] Enable HTTPS/SSL certificate (Let's Encrypt recommended)
- [ ] Configure firewall to allow only necessary ports (80, 443)
- [ ] Disable directory listing: configure web server (nginx/Apache)
- [ ] Set up WAF (Web Application Firewall) if available

### 4. Database
- [ ] Create backup of local database for reference
- [ ] Test production database connection: `php artisan tinker` → `DB::connection()->getPdo();`
- [ ] Verify charset is `utf8mb4_unicode_ci`
- [ ] Set up automated daily backups
- [ ] Verify user has minimal required permissions (not root)
- [ ] Enable SSL for database connections (if remote)

### 5. File Storage & Uploads
- [ ] Configure S3 for document storage (recommended for production)
- [ ] Update `FILESYSTEM_DISK=s3` in `.env`
- [ ] Add AWS credentials to `.env` (IAM user with S3 access only)
- [ ] Create S3 bucket with versioning + encryption enabled
- [ ] Test file upload functionality end-to-end
- [ ] Set up S3 lifecycle policy (archive after 90 days, delete after 1 year)

### 6. Email Configuration
- [ ] Configure SendGrid account (free tier available)
- [ ] Add SendGrid API key to `MAIL_PASSWORD` in `.env`
- [ ] Test email sending: `php artisan tinker` → `Mail::raw('Test', fn($m) => $m->to('test@example.com'));`
- [ ] Configure reply-to address in `.env`
- [ ] Add sender domain to SendGrid sender authentication

### 7. Payment Processing (Stripe)
- [ ] Create Stripe live account (production keys)
- [ ] Add `STRIPE_PUBLIC_KEY` (pk_live_...)
- [ ] Add `STRIPE_SECRET_KEY` (sk_live_...)
- [ ] Add `STRIPE_WEBHOOK_SECRET` (whsec_...)
- [ ] Configure webhook URL: `https://yourdomain.com/webhooks/stripe`
- [ ] Test payment flow with test card: 4242 4242 4242 4242

### 8. SSL/TLS Certificate
- [ ] Obtain SSL certificate (Let's Encrypt free option)
- [ ] Install certificate (auto via Certbot if available)
- [ ] Configure HTTPS redirect (nginx/Apache)
- [ ] Update `APP_URL=https://yourdomain.com` in `.env`
- [ ] Test SSL: `https://www.ssllabs.com/ssltest/`
- [ ] Set up auto-renewal (Certbot with cron)

### 9. Logging & Monitoring
- [ ] Set up Sentry for error tracking (free tier available)
- [ ] Add `SENTRY_LARAVEL_DSN` to `.env`
- [ ] Configure `LOG_CHANNEL=stack` with sentry driver
- [ ] Set up uptime monitoring (Pingdom, UptimeRobot)
- [ ] Configure health check endpoint: `GET /health`
- [ ] Set up alerts for errors, downtime

### 10. Performance
- [ ] Enable Redis caching: `CACHE_STORE=redis`
- [ ] Enable query caching for frequent queries
- [ ] Enable view caching: `php artisan view:cache`
- [ ] Disable `APP_DEBUG` and Debugbar in production
- [ ] Configure CDN for static assets (CloudFlare free tier)
- [ ] Test page load times: GTmetrix, PageSpeed Insights

### 11. Backup & Recovery
- [ ] Set up automated daily database backups
- [ ] Set up automated daily file backups (documents from S3)
- [ ] Test restoration process (monthly)
- [ ] Document recovery procedures
- [ ] Store backups in secondary location (different AWS region)
- [ ] Verify 30-day backup retention

### 12. Web Server Configuration
- [ ] Update web server config (nginx.conf or .htaccess)
- [ ] Enable gzip compression
- [ ] Set cache headers for static assets
- [ ] Configure error pages (404, 500, etc.)
- [ ] Block access to sensitive files (.env, .git, etc.)
- [ ] Set up security headers (HSTS, CSP, etc.)

### 13. Load Balancer & Auto-Scaling (if applicable)
- [ ] Configure load balancer health check to `/health/ready`
- [ ] Set up session persistence (sticky sessions or Redis)
- [ ] Configure auto-scaling rules
- [ ] Test failover scenarios
- [ ] Document scaling procedures

### 14. DNS Configuration
- [ ] Point domain to production server IP or load balancer
- [ ] Verify DNS propagation: `nslookup yourdomain.com`
- [ ] Set up DNS records: A, AAAA, MX (if using email)
- [ ] Configure DDoS protection (Cloudflare or similar)
- [ ] Set up email forwarding for admin emails

### 15. Testing & Validation
- [ ] Test worker portal login flow
- [ ] Test task creation and Kanban board drag-drop
- [ ] Test document upload and download
- [ ] Test messaging between workers
- [ ] Test billing dashboard (no live payments yet)
- [ ] Test analytics dashboard
- [ ] Test error handling (500 error page)
- [ ] Test CORS headers on API endpoints
- [ ] Test rate limiting (try 100 requests in 10 seconds)
- [ ] Load test: 50+ concurrent users

---

## Deployment Steps

### Step 1: Prepare Production Server

```bash
# SSH into production server
ssh deploy@yourdomain.com

# Create application directory
mkdir -p /var/www/lenium-legal
cd /var/www/lenium-legal

# Set permissions
chmod 755 /var/www/lenium-legal
chown deploy:www-data /var/www/lenium-legal -R
```

### Step 2: Deploy Application Code

```bash
# Clone repository (or extract from zip)
git clone https://github.com/yourusername/lenium-legal.git .

# Or if pushing from local:
# git push production main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Build assets (if using Vite/Webpack)
npm install
npm run build
```

### Step 3: Configure Environment

```bash
# Copy production .env
cp .env.production .env

# Edit .env with production values
nano .env

# Generate app key
php artisan key:generate

# Set permissions
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
```

### Step 4: Database & Migrations

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (if needed)
php artisan db:seed --class=InitialSeeder --force
```

### Step 5: Cache Configuration

```bash
# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Create storage link for uploads
php artisan storage:link
```

### Step 6: Web Server Configuration

#### For Nginx:

```nginx
# /etc/nginx/sites-available/yourdomain.com

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;

    # SSL certificates
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    # Security settings
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # HSTS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;

    root /var/www/lenium-legal/public;
    index index.php;

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ /\. {
        deny all;
    }

    location ~ /\.git {
        deny all;
    }

    location ~ /\.env {
        deny all;
    }
}

# HTTP redirect to HTTPS
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    
    return 301 https://$server_name$request_uri;
}
```

Apply configuration:
```bash
sudo nginx -t
sudo systemctl reload nginx
```

#### For Apache (.htaccess):

```apache
# Create /var/www/lenium-legal/public/.htaccess

<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect HTTP to HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

    # Deny access to sensitive files
    RewriteRule ^\.env$ - [F,L]
    RewriteRule ^\.git - [F,L]
    RewriteRule ^storage - [F,L]
</IfModule>
```

### Step 7: Queue Worker Setup (optional, for async jobs)

```bash
# Create systemd service for queue worker
sudo nano /etc/systemd/system/lenium-queue.service

[Unit]
Description=Lenium Legal Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/lenium-legal
ExecStart=/usr/bin/php artisan queue:work --sleep=3 --tries=3
Restart=on-failure
RestartSec=10

[Install]
WantedBy=multi-user.target

# Enable and start
sudo systemctl enable lenium-queue
sudo systemctl start lenium-queue
```

### Step 8: Cron Jobs Setup

```bash
# Add to crontab
sudo crontab -e

# Le Nium Legal scheduled tasks
* * * * * cd /var/www/lenium-legal && php artisan schedule:run >> /dev/null 2>&1

# Backup database (daily at 2 AM)
0 2 * * * mysqldump -u root -pPASSWORD leniumlegalops_prod | gzip > /backups/db-$(date +\%Y\%m\%d).sql.gz
```

### Step 9: Monitoring & Alerts

```bash
# Set up health check monitoring
# Add to uptime monitoring service (UptimeRobot, Pingdom, etc.)
# URL: https://yourdomain.com/health
# Interval: Every 5 minutes
# Alert if down for > 5 minutes
```

---

## Post-Deployment Verification

```bash
# Test application
curl https://yourdomain.com/health

# Check logs
tail -f /var/www/lenium-legal/storage/logs/laravel.log

# Verify caching
php artisan tinker
>>> Cache::put('test', 'value', 3600)
>>> Cache::get('test')

# Test database
>>> DB::connection()->getPdo()

# Monitor performance
# Check nginx/Apache access logs
tail -f /var/log/nginx/access.log

# Monitor resource usage
top
df -h
free -h
```

---

## Rollback Procedure (if needed)

```bash
# If deployment fails:

# 1. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Rollback migrations
php artisan migrate:rollback --force

# 3. Restore from git
git reset --hard HEAD~1

# 4. Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx

# 5. Clear caches again
php artisan optimize:clear
```

---

## Security Hardening

### Regular Updates

```bash
# Weekly
composer update
php artisan package:update

# Monthly
phpscan scan . # Check for vulnerabilities
composer audit # Check for security issues
```

### Automated Security Scanning

- Enable GitHub Dependabot for dependency updates
- Use Laravel Security Checker: `composer require enlightn/security-checker`
- Regularly scan logs for suspicious activity

### Access Control

- Limit SSH access (IP whitelist)
- Disable password authentication (use key-based only)
- Use fail2ban to block brute-force attempts
- Regularly review access logs

### Data Protection

- Enable encryption at rest (S3 server-side encryption)
- Use SSL/TLS for all connections
- Enable database encryption (if supported)
- Regular security audits (quarterly)

---

## Maintenance

### Daily Tasks
- Check error logs for issues
- Verify backups completed
- Monitor uptime status

### Weekly Tasks
- Review security logs
- Check performance metrics
- Verify all systems operational

### Monthly Tasks
- Security updates (composer, npm)
- Database optimization
- Review and update security policies

### Quarterly Tasks
- Full security audit
- Performance optimization review
- Disaster recovery test

---

## Support & Troubleshooting

### Common Issues

**Issue**: 500 Error on login
```bash
# Solution
php artisan cache:clear
php artisan config:clear
php artisan queue:restart
```

**Issue**: Document uploads failing
```bash
# Solution
# Check storage permissions
chmod -R 775 storage/
# Verify S3 credentials
php artisan tinker
>>> Storage::disk('s3')->put('test.txt', 'test')
```

**Issue**: High memory usage
```bash
# Solution
# Increase PHP memory limit
# Edit php.ini: memory_limit = 512M
# Restart PHP-FPM: sudo systemctl restart php-fpm
```

**Issue**: Slow page loads
```bash
# Solution
# Enable Redis caching
# Check query performance with Laravel Debugbar
# Consider CDN for static assets
```

---

## Documentation & Handoff

- [ ] Create runbook with emergency procedures
- [ ] Document all credentials (securely, in password manager)
- [ ] Create on-call rotation schedule
- [ ] Document escalation procedures
- [ ] Schedule monthly security reviews
- [ ] Set up developer notification channels

---

**Deployment Ready**: ✅ All systems operational

For questions or issues, check [SUPPORT.md](SUPPORT.md) or contact security@yourdomain.com
