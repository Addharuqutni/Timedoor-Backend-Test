# Deployment Guide

## Overview
Panduan lengkap untuk deploy aplikasi Timedoor Backend Test ke berbagai environment.

## Prerequisites

### Server Requirements
- **PHP**: 8.2 atau lebih tinggi
- **Web Server**: Apache atau Nginx
- **Database**: Mysql
- **Composer**: Package manager untuk PHP
- **Git**: Untuk version control

### PHP Extensions Required
```bash
# Ubuntu/Debian
sudo apt-get install php8.2-cli php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl

# CentOS/RHEL
sudo yum install php php-cli php-fpm php-mysql php-mbstring php-xml php-curl

# MySQL Server Installation
# Ubuntu/Debian
sudo apt-get install mysql-server

# CentOS/RHEL
sudo yum install mysql-server
```

## MySQL Database Setup

### MySQL Installation dan Configuration

#### Ubuntu/Debian
```bash
# Install MySQL Server
sudo apt update
sudo apt install mysql-server

# Secure MySQL installation
sudo mysql_secure_installation

# Login ke MySQL sebagai root
sudo mysql -u root -p
```

#### CentOS/RHEL
```bash
# Install MySQL Server
sudo yum install mysql-server

# Start dan enable MySQL service
sudo systemctl start mysqld
sudo systemctl enable mysqld

# Get temporary root password
sudo grep 'temporary password' /var/log/mysqld.log

# Secure installation
sudo mysql_secure_installation
```

### Database dan User Setup
```sql
-- Login ke MySQL sebagai root
mysql -u root -p

-- Create database dengan charset yang tepat
CREATE DATABASE timedoor_backend 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Create user untuk aplikasi
CREATE USER 'timedoor_user'@'localhost' IDENTIFIED BY 'SecurePassword123!';

-- Grant privileges
GRANT ALL PRIVILEGES ON timedoor_backend.* TO 'timedoor_user'@'localhost';

-- Untuk production, gunakan privileges yang lebih terbatas:
-- GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, ALTER, INDEX, DROP ON timedoor_backend.* TO 'timedoor_user'@'localhost';

-- Apply changes
FLUSH PRIVILEGES;

-- Verify user creation
SELECT User, Host FROM mysql.user WHERE User = 'timedoor_user';

-- Exit MySQL
EXIT;
```

### MySQL Configuration Optimization
```bash
# Edit MySQL configuration
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# Add optimizations for Laravel:
[mysqld]
innodb_buffer_pool_size = 256M
innodb_log_file_size = 64M
innodb_flush_log_at_trx_commit = 2
innodb_flush_method = O_DIRECT
query_cache_type = 1
query_cache_size = 64M
tmp_table_size = 64M
max_heap_table_size = 64M

# Restart MySQL
sudo systemctl restart mysql
```

### Laravel Migration untuk MySQL
```bash
# Update config/database.php jika diperlukan
php artisan config:clear

# Run migrations
php artisan migrate

# Check migration status
php artisan migrate:status

# Seed database
php artisan db:seed
```

## Shared Hosting Deployment

### Step 1: Upload Files
```bash
# Compress project files (exclude vendor and .env)
zip -r timedoor-backend.zip . -x vendor/\* .env

# Upload dan extract di hosting
unzip timedoor-backend.zip
```

### Step 2: Install Dependencies
```bash
# Pastikan composer tersedia di hosting
composer install --optimize-autoloader --no-dev
```

### Step 3: Environment Configuration
```bash
# Copy dan edit .env file
cp .env.example .env

# Edit .env file untuk production
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your_app_key_here

# Database configuration untuk MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timedoor_backend
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### Step 4: Database Setup
```bash
# MySQL Database Setup
# Login ke MySQL sebagai root
mysql -u root -p

# Create database dan user
CREATE DATABASE timedoor_backend CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'timedoor_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON timedoor_backend.* TO 'timedoor_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force

# Seed database (optional untuk production)
php artisan db:seed
```

### Step 5: Permissions
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Pastikan user web server dapat mengakses aplikasi
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Step 6: Web Server Configuration

#### Apache (.htaccess)
```apache
# public/.htaccess
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

## VPS Deployment

### Step 1: Server Setup (Ubuntu 22.04)
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx php8.2-fpm php8.2-cli php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl unzip git mysql-server

# Secure MySQL installation
sudo mysql_secure_installation

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Step 2: Clone dan Setup Project
```bash
# Clone repository
git clone https://github.com/Addharuqutni/Timedoor-Backend-Test.git
cd Timedoor-Backend-Test

# Install dependencies
composer install --optimize-autoloader --no-dev

# Environment setup
cp .env.example .env
php artisan key:generate
```

### Step 3: Database Configuration
```bash
# Setup MySQL Database
sudo mysql -u root -p

# Di dalam MySQL console:
CREATE DATABASE timedoor_backend CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'timedoor_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON timedoor_backend.* TO 'timedoor_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Update .env file dengan database credentials
nano .env
# Set database configuration:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=timedoor_backend
# DB_USERNAME=timedoor_user
# DB_PASSWORD=secure_password_here

# Run migrations
php artisan migrate --force

# Optional: Seed database
php artisan db:seed
```

### Step 4: Nginx Configuration
```nginx
# /etc/nginx/sites-available/timedoor-backend
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/timedoor-backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### Step 5: Enable Site
```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/timedoor-backend /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### Step 6: Set Permissions
```bash
# Change ownership
sudo chown -R www-data:www-data /var/www/timedoor-backend

# Set permissions
sudo chmod -R 755 /var/www/timedoor-backend
sudo chmod -R 775 /var/www/timedoor-backend/storage
sudo chmod -R 775 /var/www/timedoor-backend/bootstrap/cache
```

## Docker Deployment

### Dockerfile
```dockerfile
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring xml

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
```

### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: timedoor-backend
    volumes:
      - .:/var/www
    networks:
      - timedoor-network
    depends_on:
      - mysql

  nginx:
    image: nginx:alpine
    container_name: timedoor-nginx
    ports:
      - "80:80"
    volumes:
      - .:/var/www
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - timedoor-network

  mysql:
    image: mysql:8.0
    container_name: timedoor-mysql
    environment:
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_DATABASE: timedoor_backend
      MYSQL_USER: timedoor_user
      MYSQL_PASSWORD: user_password
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - timedoor-network

networks:
  timedoor-network:
    driver: bridge

volumes:
  mysql_data:
```

### Docker Nginx Configuration
```nginx
# docker/nginx.conf
server {
    listen 80;
    index index.php index.html;
    error_log /var/log/nginx/error.log;
    access_log /var/log/nginx/access.log;
    root /var/www/public;

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
        gzip_static on;
    }
}
```

### Deploy with Docker
```bash
# Create .env file for Docker
cp .env.example .env

# Edit .env untuk Docker MySQL
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=timedoor_backend
DB_USERNAME=timedoor_user
DB_PASSWORD=user_password

# Build dan run
docker-compose up -d --build

# Generate application key
docker-compose exec app php artisan key:generate

# Run migrations inside container
docker-compose exec app php artisan migrate --force

# Optional: Seed database
docker-compose exec app php artisan db:seed
```

## SSL/HTTPS Setup

### Let's Encrypt with Certbot
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d your-domain.com

# Verify auto-renewal
sudo certbot renew --dry-run
```

### Manual SSL Certificate
```nginx
# Add to nginx configuration
server {
    listen 443 ssl http2;
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    
    # SSL configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;
    
    # ... rest of configuration
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}
```

## Environment-Specific Configurations

### Production .env
```env
APP_NAME="Timedoor Backend"
APP_ENV=production
APP_KEY=base64:your_generated_key_here
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timedoor_backend
DB_USERNAME=timedoor_user
DB_PASSWORD=secure_database_password

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

BROADCAST_DRIVER=log
```

### Staging .env
```env
APP_NAME="Timedoor Backend (Staging)"
APP_ENV=staging
APP_DEBUG=true
APP_URL=https://staging.your-domain.com

LOG_LEVEL=debug

# Database config untuk staging
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=timedoor_backend_staging
DB_USERNAME=timedoor_user
DB_PASSWORD=secure_staging_password
```

## Monitoring & Maintenance

### Log Monitoring
```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View Nginx logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/nginx/access.log

# View PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log

# View MySQL logs
sudo tail -f /var/log/mysql/error.log
sudo tail -f /var/log/mysql/mysql.log

# Monitor MySQL processes
mysql -u root -p -e "SHOW PROCESSLIST;"

# Check MySQL status
mysql -u root -p -e "SHOW STATUS LIKE 'Threads_connected';"
mysql -u root -p -e "SHOW STATUS LIKE 'Queries';"
```

### Backup Strategy
```bash
#!/bin/bash
# backup.sh

# Variables
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/timedoor-backend"
APP_DIR="/var/www/timedoor-backend"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u timedoor_user -p timedoor_backend > $BACKUP_DIR/database_$DATE.sql

# Backup uploaded files (if any)
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C $APP_DIR storage/app/public

# Backup .env file
cp $APP_DIR/.env $BACKUP_DIR/env_$DATE.backup

# Remove old backups (keep last 7 days)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete
find $BACKUP_DIR -name "*.backup" -mtime +7 -delete

echo "Backup completed: $DATE"
```

### Cron Jobs
```bash
# Edit crontab
crontab -e

# Add backup job (daily at 2 AM)
0 2 * * * /path/to/backup.sh

# Laravel scheduled tasks (if any)
* * * * * cd /var/www/timedoor-backend && php artisan schedule:run >> /dev/null 2>&1
```

## Performance Optimization

### Laravel Optimizations
```bash
# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Clear caches when needed
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Nginx Optimizations
```nginx
# Add to nginx.conf
http {
    # Enable gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/javascript application/xml+rss application/json;

    # Cache static files
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Client body size
    client_max_body_size 100M;
}
```

### Database Optimizations
```sql
-- Add indexes for better performance
CREATE INDEX idx_books_title ON books(title);
CREATE INDEX idx_books_author_id ON books(author_id);
CREATE INDEX idx_ratings_book_id ON ratings(book_id);
CREATE INDEX idx_ratings_rating ON ratings(rating);
```

## Troubleshooting

### Common Issues

1. **Permission Denied Errors**
```bash
sudo chown -R www-data:www-data /var/www/timedoor-backend
sudo chmod -R 775 storage bootstrap/cache
```

2. **Database Connection Error**
```bash
# Check MySQL service status
sudo systemctl status mysql

# Start MySQL if not running
sudo systemctl start mysql

# Check database credentials in .env file
mysql -u timedoor_user -p -e "SHOW DATABASES;"
```

3. **500 Internal Server Error**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check web server logs
tail -f /var/log/nginx/error.log
```

4. **Composer Memory Issues**
```bash
# Increase memory limit
php -d memory_limit=512M /usr/local/bin/composer install
```

### MySQL Specific Issues

5. **MySQL Connection Refused**
```bash
# Check MySQL service status
sudo systemctl status mysql

# Start MySQL service
sudo systemctl start mysql

# Check if MySQL is listening on correct port
netstat -tlnp | grep :3306

# Check MySQL error log
sudo tail -f /var/log/mysql/error.log
```

6. **Access Denied for User**
```bash
# Reset MySQL root password if needed
sudo systemctl stop mysql
sudo mysqld_safe --skip-grant-tables &
mysql -u root
UPDATE mysql.user SET authentication_string=PASSWORD('new_password') WHERE User='root';
FLUSH PRIVILEGES;
exit;
sudo systemctl restart mysql

# Verify user exists and has correct privileges
mysql -u root -p
SELECT User, Host FROM mysql.user WHERE User = 'timedoor_user';
SHOW GRANTS FOR 'timedoor_user'@'localhost';
```

7. **Database Character Set Issues**
```bash
# Check database charset
mysql -u timedoor_user -p
SHOW CREATE DATABASE timedoor_backend;

# If wrong charset, recreate database:
DROP DATABASE timedoor_backend;
CREATE DATABASE timedoor_backend CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

8. **Laravel Migration Errors**
```bash
# Clear Laravel caches
php artisan cache:clear
php artisan config:clear

# Check database connection
php artisan tinker
DB::connection()->getPdo();

# Run migrations with verbose output
php artisan migrate --verbose

# Rollback and retry if needed
php artisan migrate:rollback
php artisan migrate
```

### Health Check Script
```bash
#!/bin/bash
# health_check.sh

# Check if web server is running
if ! pgrep nginx > /dev/null; then
    echo "Nginx is not running"
    sudo systemctl start nginx
fi

# Check if PHP-FPM is running
if ! pgrep php-fpm > /dev/null; then
    echo "PHP-FPM is not running"
    sudo systemctl start php8.2-fpm
fi

# Check if MySQL is running
if ! pgrep mysqld > /dev/null; then
    echo "MySQL is not running"
    sudo systemctl start mysql
fi

# Check database connectivity
if ! mysql -u timedoor_user -p'your_password' -e 'SELECT 1' timedoor_backend > /dev/null 2>&1; then
    echo "Database connection failed"
fi

# Check MySQL connections
MYSQL_CONNECTIONS=$(mysql -u root -p'root_password' -e "SHOW STATUS LIKE 'Threads_connected';" | awk 'NR==2{print $2}')
if [ $MYSQL_CONNECTIONS -gt 100 ]; then
    echo "High number of MySQL connections: $MYSQL_CONNECTIONS"
fi

# Check disk space
DISK_USAGE=$(df / | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 90 ]; then
    echo "Disk usage is above 90%"
fi

echo "Health check completed"
```

## Security Considerations

### Server Security
```bash
# Update system regularly
sudo apt update && sudo apt upgrade

# Configure firewall
sudo ufw enable
sudo ufw allow 22
sudo ufw allow 80
sudo ufw allow 443

# Disable unnecessary services
sudo systemctl disable apache2  # if using nginx
```

### Application Security
```env
# In .env file
APP_DEBUG=false
APP_ENV=production

# Hide server information
expose_php=Off  # in php.ini
server_tokens=off;  # in nginx.conf
```

### Database Security
```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create dedicated database user dengan minimal privileges
mysql -u root -p
CREATE USER 'timedoor_app'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT SELECT, INSERT, UPDATE, DELETE ON timedoor_backend.* TO 'timedoor_app'@'localhost';
FLUSH PRIVILEGES;

# Regular database backups
# Implement the backup strategy mentioned above
```
