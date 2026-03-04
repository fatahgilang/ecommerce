# Panduan Deployment E-Commerce

## Persyaratan Sistem

### Versi Software yang Direkomendasikan

| Software | Versi Minimum | Versi Direkomendasikan | Versi Development |
|----------|---------------|------------------------|-------------------|
| **PHP** | 8.2.0 | 8.2.x atau 8.3.x | 8.5.0 |
| **Node.js** | 20.0.0 | 20.x LTS atau 22.x LTS | 24.7.0 |
| **NPM** | 10.0.0 | 10.x | 11.5.1 |
| **Composer** | 2.5.0 | 2.9.x | 2.9.3 |
| **MySQL** | 8.0.0 | 8.0.27+ | 8.0.27 |
| **Laravel** | 12.0 | 12.26.x | 12.26.3 |

### File Konfigurasi Versi

Proyek ini menyertakan file-file berikut untuk memastikan konsistensi versi:

- `.node-version` - Versi Node.js (24.7.0 untuk development, gunakan 20.x atau 22.x untuk production)
- `.nvmrc` - Konfigurasi NVM (Node Version Manager)
- `.php-version` - Versi PHP (8.2 untuk kompatibilitas maksimal)
- `composer.json` - Dependency PHP dengan constraint versi
- `package.json` - Dependency Node.js dengan engines specification

## Dependency Utama

### PHP Dependencies (composer.json)

```json
{
  "require": {
    "php": "^8.2",
    "filament/filament": "^3.3",
    "inertiajs/inertia-laravel": "^2.0",
    "laravel/framework": "^12.0",
    "laravel/sanctum": "^4.2",
    "laravel/tinker": "^2.10.1",
    "tightenco/ziggy": "^2.6"
  }
}
```

### Node.js Dependencies (package.json)

```json
{
  "dependencies": {
    "@heroicons/vue": "^2.2.0",
    "@inertiajs/vue3": "^2.3.17",
    "@vitejs/plugin-vue": "^6.0.4",
    "vue": "^3.5.29"
  },
  "devDependencies": {
    "@tailwindcss/vite": "^4.0.0",
    "axios": "^1.11.0",
    "laravel-vite-plugin": "^2.0.0",
    "tailwindcss": "^4.0.0",
    "vite": "^7.0.4"
  }
}
```

## Persiapan Deployment

### 1. Clone Repository

```bash
git clone <repository-url>
cd ecommerce
```

### 2. Setup Environment

```bash
# Copy environment file
cp .env.example .env

# Edit .env sesuai dengan konfigurasi server
nano .env
```

### 3. Konfigurasi Database di .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecomerce
DB_USERNAME=root
DB_PASSWORD=

# Untuk production, gunakan kredensial yang aman
```

### 4. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node.js dependencies
npm ci --production=false
```

**Note untuk PHP 8.5 (Development):**
Jika menggunakan PHP 8.5, tambahkan flag:
```bash
composer install --ignore-platform-reqs
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Setup Database

```bash
# Run migrations
php artisan migrate --force

# Seed database (optional untuk production)
php artisan db:seed --force
```

### 7. Setup Storage

```bash
# Create storage link
php artisan storage:link

# Set permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 8. Build Frontend Assets

```bash
# Build untuk production
npm run build
```

### 9. Optimize Laravel

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

## Deployment ke Berbagai Platform

### Shared Hosting (cPanel)

1. **Upload Files**
   - Upload semua file kecuali `node_modules` dan `vendor`
   - Upload ke folder di luar `public_html`

2. **Setup Public Directory**
   - Pindahkan isi folder `public` ke `public_html`
   - Update `index.php` di `public_html`:
   ```php
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';
   ```

3. **Setup Database**
   - Buat database via cPanel
   - Import atau jalankan migrations

4. **Install Dependencies**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci && npm run build
   ```

5. **Set Permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   ```

### VPS (Ubuntu/Debian)

1. **Install Requirements**
   ```bash
   # Update system
   sudo apt update && sudo apt upgrade -y

   # Install PHP 8.2
   sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd

   # Install MySQL
   sudo apt install mysql-server

   # Install Node.js 20.x LTS
   curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
   sudo apt install nodejs

   # Install Composer
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer

   # Install Nginx
   sudo apt install nginx
   ```

2. **Configure Nginx**
   ```nginx
   server {
       listen 80;
       server_name yourdomain.com;
       root /var/www/ecommerce/public;

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

3. **Setup SSL (Let's Encrypt)**
   ```bash
   sudo apt install certbot python3-certbot-nginx
   sudo certbot --nginx -d yourdomain.com
   ```

4. **Setup Queue Worker (Optional)**
   ```bash
   # Create supervisor config
   sudo nano /etc/supervisor/conf.d/laravel-worker.conf
   ```

   ```ini
   [program:laravel-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/ecommerce/artisan queue:work --sleep=3 --tries=3
   autostart=true
   autorestart=true
   user=www-data
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/var/www/ecommerce/storage/logs/worker.log
   ```

   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start laravel-worker:*
   ```

### Docker

1. **Create Dockerfile**
   ```dockerfile
   FROM php:8.2-fpm

   # Install dependencies
   RUN apt-get update && apt-get install -y \
       git \
       curl \
       libpng-dev \
       libonig-dev \
       libxml2-dev \
       zip \
       unzip \
       nodejs \
       npm

   # Install PHP extensions
   RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

   # Install Composer
   COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

   # Set working directory
   WORKDIR /var/www

   # Copy application
   COPY . .

   # Install dependencies
   RUN composer install --no-dev --optimize-autoloader
   RUN npm ci && npm run build

   # Set permissions
   RUN chown -R www-data:www-data /var/www
   RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

   EXPOSE 9000
   CMD ["php-fpm"]
   ```

2. **Create docker-compose.yml**
   ```yaml
   version: '3.8'
   services:
     app:
       build: .
       container_name: ecommerce-app
       restart: unless-stopped
       working_dir: /var/www
       volumes:
         - ./:/var/www
       networks:
         - ecommerce

     nginx:
       image: nginx:alpine
       container_name: ecommerce-nginx
       restart: unless-stopped
       ports:
         - "80:80"
       volumes:
         - ./:/var/www
         - ./docker/nginx:/etc/nginx/conf.d
       networks:
         - ecommerce

     mysql:
       image: mysql:8.0
       container_name: ecommerce-mysql
       restart: unless-stopped
       environment:
         MYSQL_DATABASE: ecomerce
         MYSQL_ROOT_PASSWORD: secret
       volumes:
         - mysql-data:/var/lib/mysql
       networks:
         - ecommerce

   networks:
     ecommerce:
       driver: bridge

   volumes:
     mysql-data:
   ```

## Troubleshooting

### PHP Version Issues

**Problem:** PHP 8.5 deprecation warnings
```
Deprecated: Constant PDO::MYSQL_ATTR_SSL_CA is deprecated
```

**Solution:**
1. Gunakan PHP 8.2 atau 8.3 untuk production
2. Atau tambahkan error suppression di `bootstrap/app.php`:
```php
error_reporting(E_ALL & ~E_DEPRECATED);
```

### Composer Install Fails

**Problem:** Platform requirements not satisfied

**Solution:**
```bash
composer install --ignore-platform-reqs
```

### Storage Permission Issues

**Problem:** Permission denied errors

**Solution:**
```bash
# Linux/Mac
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Atau untuk development
chmod -R 777 storage bootstrap/cache
```

### Database Connection Issues

**Problem:** SQLSTATE[HY000] [2002] Connection refused

**Solution:**
1. Cek MySQL service: `sudo systemctl status mysql`
2. Cek kredensial di `.env`
3. Cek firewall: `sudo ufw allow 3306`

### Build Assets Fails

**Problem:** npm run build fails

**Solution:**
```bash
# Clear cache
rm -rf node_modules package-lock.json
npm cache clean --force

# Reinstall
npm install
npm run build
```

## Checklist Deployment

- [ ] Environment file configured (`.env`)
- [ ] Database created and credentials set
- [ ] Composer dependencies installed
- [ ] NPM dependencies installed
- [ ] Application key generated
- [ ] Database migrated
- [ ] Storage linked
- [ ] Frontend assets built
- [ ] Laravel optimized (config, route, view cache)
- [ ] File permissions set correctly
- [ ] SSL certificate installed (production)
- [ ] Backup strategy implemented
- [ ] Monitoring setup (optional)
- [ ] Queue worker running (if needed)

## Maintenance

### Update Application

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Backup Database

```bash
# Manual backup
mysqldump -u root -p ecomerce > backup_$(date +%Y%m%d).sql

# Automated backup (cron)
0 2 * * * mysqldump -u root -p'password' ecomerce > /backups/db_$(date +\%Y\%m\%d).sql
```

### Monitor Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.2-fpm.log
```

## Security Checklist

- [ ] `.env` file not in version control
- [ ] Strong database passwords
- [ ] APP_DEBUG=false in production
- [ ] APP_ENV=production
- [ ] HTTPS enabled
- [ ] File upload validation
- [ ] CSRF protection enabled
- [ ] SQL injection prevention (use Eloquent)
- [ ] XSS protection
- [ ] Regular security updates
- [ ] Backup strategy in place

## Performance Optimization

### Laravel Optimization

```bash
# Cache everything
php artisan optimize

# Or individually
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Database Optimization

```sql
-- Analyze tables
ANALYZE TABLE products, orders, transactions;

-- Optimize tables
OPTIMIZE TABLE products, orders, transactions;
```

### Nginx Caching

```nginx
# Add to nginx config
location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

## Support

Untuk bantuan lebih lanjut:
- Email: fatahgilang23@gmail.com
- WhatsApp: 082333058317
- Documentation: `/docs` folder

## Changelog

### Version 1.0.0 (2026-03-03)
- Initial release
- Filament Admin Panel
- Inertia.js Frontend
- Product Management
- Order Management
- Transaction Management
- Database Indexing
- Dummy Images
