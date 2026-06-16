# Basira AI — Production Deployment Guide

Tested on Ubuntu 22.04 LTS with Apache 2.4 and MySQL 8.0.

---

## 1. Server Requirements

| Requirement | Minimum |
|---|---|
| PHP | 8.1+ |
| MySQL | 8.0+ |
| Web server | Apache 2.4 or Nginx 1.18+ |
| RAM | 512 MB |
| Disk | 500 MB |

**Required PHP extensions:**
```bash
php -m | grep -E "pdo|pdo_mysql|json|mbstring|openssl|session"
```
All of these must appear. Install any missing ones:
```bash
sudo apt install php8.1-pdo php8.1-mysql php8.1-mbstring php8.1-json
```

---

## 2. Deploy the Code

```bash
# Clone to your preferred directory
cd /var/www
sudo git clone https://github.com/DrMohamedFawzi/Basera-ai basira-ai
cd basira-ai

# Install production dependencies only (no dev/tests)
sudo composer install --no-dev --optimize-autoloader --no-interaction
```

---

## 3. Environment Configuration

```bash
# Create .env from example
sudo cp .env.example .env
sudo nano .env
```

Fill in your production values:
```dotenv
DB_HOST=localhost
DB_NAME=basira_ai
DB_USER=basira_user
DB_PASS=STRONG_PASSWORD_HERE
```

Lock down the file so only the web server user can read it:
```bash
sudo chown www-data:www-data .env
sudo chmod 640 .env
```

---

## 4. Database Setup

**Create the database and a dedicated user** (never use `root` in production):

```sql
-- Run as MySQL root
CREATE DATABASE basira_ai CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'basira_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT SELECT, INSERT, UPDATE, DELETE ON basira_ai.* TO 'basira_user'@'localhost';
FLUSH PRIVILEGES;
```

**Run the schema then seed data:**
```bash
mysql -u basira_user -p basira_ai < database/schema.sql
mysql -u basira_user -p basira_ai < database/seed.sql
```

Verify:
```bash
mysql -u basira_user -p basira_ai -e "SELECT COUNT(*) FROM questions; SELECT COUNT(*) FROM careers;"
```
Should return 19 questions and 10 careers.

---

## 5. File Permissions

The document root is `public/`. Only `public/` should be web-accessible. The rest of the project must sit above it:

```bash
# Ownership: web server user owns the files
sudo chown -R www-data:www-data /var/www/basira-ai

# Directories: traversable
sudo find /var/www/basira-ai -type d -exec chmod 755 {} \;

# Files: readable, not executable
sudo find /var/www/basira-ai -type f -exec chmod 644 {} \;

# .env: restricted
sudo chmod 640 /var/www/basira-ai/.env
```

---

## 6. Apache Virtual Host

Create `/etc/apache2/sites-available/basira-ai.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/basira-ai/public

    <Directory /var/www/basira-ai/public>
        Options -Indexes -ExecCGI
        AllowOverride All
        Require all granted
    </Directory>

    # Block access to sensitive directories above public/
    <Directory /var/www/basira-ai>
        Require all denied
    </Directory>
    <Directory /var/www/basira-ai/public>
        Require all granted
    </Directory>

    ErrorLog  ${APACHE_LOG_DIR}/basira-ai-error.log
    CustomLog ${APACHE_LOG_DIR}/basira-ai-access.log combined
</VirtualHost>
```

Enable and reload:
```bash
sudo a2enmod rewrite
sudo a2ensite basira-ai
sudo a2dissite 000-default   # disable default site if not needed
sudo systemctl reload apache2
```

---

## 7. Nginx Virtual Host (alternative)

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/basira-ai/public;
    index index.php;

    # Route all requests to index.php
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
    }

    # Block access to hidden files (.env, .git, etc.)
    location ~ /\. {
        deny all;
    }

    error_log  /var/log/nginx/basira-ai-error.log;
    access_log /var/log/nginx/basira-ai-access.log;
}
```

```bash
sudo ln -s /etc/nginx/sites-available/basira-ai /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

---

## 8. Clean URLs (.htaccess)

The `public/` directory already receives all traffic. Add a `.htaccess` so URLs work as `/login` instead of `/index.php/login`:

```bash
sudo nano /var/www/basira-ai/public/.htaccess
```

```apache
Options -Indexes
RewriteEngine On

# Redirect to HTTPS (uncomment after SSL is configured)
# RewriteCond %{HTTPS} off
# RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L]

# Route everything to index.php except real files
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]

# Block direct access to sensitive files
<FilesMatch "\.(env|log|sql|lock|json|xml)$">
    Require all denied
</FilesMatch>
```

---

## 9. PHP Production Settings

Edit your PHP ini (find it with `php --ini`), typically `/etc/php/8.1/apache2/php.ini`:

```ini
; Hide PHP errors from end users
display_errors = Off
display_startup_errors = Off

; Log errors to file instead
log_errors = On
error_log = /var/log/php/basira-ai-errors.log

; Tighten session security
session.cookie_httponly = 1
session.cookie_samesite = Strict
session.use_strict_mode = 1
; session.cookie_secure = 1   ; uncomment after SSL is set up

; Limit exposure
expose_php = Off
```

Create the log directory:
```bash
sudo mkdir -p /var/log/php
sudo chown www-data:www-data /var/log/php
sudo systemctl restart apache2   # or php8.1-fpm for Nginx
```

---

## 10. SSL with Let's Encrypt

```bash
sudo apt install certbot python3-certbot-apache   # or python3-certbot-nginx

# Apache
sudo certbot --apache -d yourdomain.com

# Nginx
sudo certbot --nginx -d yourdomain.com
```

Certbot auto-renews. Test renewal:
```bash
sudo certbot renew --dry-run
```

After SSL is live, uncomment `session.cookie_secure = 1` in your PHP ini and the HTTPS redirect in `.htaccess`.

---

## 11. Security Checklist

Run through this before going live:

```bash
# .env is NOT web-accessible
curl -I https://yourdomain.com/.env
# → Must return 403 or 404, never 200

# vendor/ is NOT web-accessible
curl -I https://yourdomain.com/vendor/autoload.php
# → Must return 403 or 404

# Directory listing is disabled
curl -I https://yourdomain.com/
# → Should return 200 with your app, not a file list

# PHP errors are hidden
# Visit a broken URL and confirm no stack trace is shown
```

Additional hardening:
```bash
# Disable unused Apache modules
sudo a2dismod status autoindex
sudo systemctl reload apache2

# Keep OS and packages updated
sudo apt update && sudo apt upgrade -y

# Set up a firewall
sudo ufw allow OpenSSH
sudo ufw allow 'Apache Full'   # or 'Nginx Full'
sudo ufw enable
```

---

## 12. Updating the App

```bash
cd /var/www/basira-ai

# Pull latest code
sudo git pull origin main

# Update dependencies
sudo composer install --no-dev --optimize-autoloader

# Run any new migrations (if schema changed)
# mysql -u basira_user -p basira_ai < database/schema.sql

# Fix permissions after pull
sudo chown -R www-data:www-data .
sudo chmod 640 .env

# Reload web server
sudo systemctl reload apache2   # or nginx
```

---

## 13. Smoke Test After Deploy

Open a browser and verify each step:

- [ ] `https://yourdomain.com/register` — registration form loads
- [ ] Register a new account → redirected to wizard
- [ ] Wizard loads all questions with progress counter
- [ ] Answer all questions → redirected to results page
- [ ] Results page shows DNA score, career matches, and roadmap
- [ ] "إعادة الاختبار" button resets and restarts the wizard
- [ ] Logout → redirected to login page
- [ ] Login with the same account → wizard loads

---

## Quick Reference

```bash
# Restart web server
sudo systemctl restart apache2   # or nginx

# View error logs
sudo tail -f /var/log/apache2/basira-ai-error.log
sudo tail -f /var/log/php/basira-ai-errors.log

# Check PHP version and loaded extensions
php -v
php -m

# Run unit tests (dev only, not on production)
vendor/bin/phpunit
```
