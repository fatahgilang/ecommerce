# Version Lock - E-Commerce Application

Dokumen ini mencatat semua versi software, library, dan dependency yang digunakan dalam proyek ini untuk memastikan konsistensi deployment.

## Runtime Environment

### PHP
- **Development**: 8.5.0 (Laravel Herd)
- **Production Recommended**: 8.2.x atau 8.3.x
- **Minimum**: 8.2.0
- **Constraint**: `^8.2` (composer.json)

**Note**: PHP 8.5 masih dalam development dan memiliki deprecation warnings. Gunakan PHP 8.2 atau 8.3 untuk production.

### Node.js
- **Development**: 24.7.0
- **Production Recommended**: 20.x LTS atau 22.x LTS
- **Minimum**: 20.0.0
- **Constraint**: `>=20.0.0` (package.json engines)

### NPM
- **Development**: 11.5.1
- **Production Recommended**: 10.x
- **Minimum**: 10.0.0
- **Constraint**: `>=10.0.0` (package.json engines)

### Composer
- **Development**: 2.9.3
- **Production Recommended**: 2.9.x
- **Minimum**: 2.5.0

### MySQL
- **Development**: 8.0.27
- **Production Recommended**: 8.0.27+
- **Minimum**: 8.0.0

## Framework & Core Libraries

### Laravel
- **Version**: 12.26.3
- **Constraint**: `^12.0`
- **Release**: Latest stable

### Vue.js
- **Version**: 3.5.29
- **Constraint**: `^3.5.29`
- **Type**: Frontend Framework

### Vite
- **Version**: 7.0.4
- **Constraint**: `^7.0.4`
- **Type**: Build Tool

## PHP Dependencies (Composer)

### Production Dependencies

| Package | Version | Constraint | Purpose |
|---------|---------|------------|---------|
| laravel/framework | 12.26.3 | ^12.0 | Core Framework |
| filament/filament | 3.3.x | ^3.3 | Admin Panel |
| inertiajs/inertia-laravel | 2.x | ^2.0 | SPA Adapter |
| laravel/sanctum | 4.2.x | ^4.2 | API Authentication |
| laravel/tinker | 2.10.1 | ^2.10.1 | REPL Tool |
| tightenco/ziggy | 2.6.x | ^2.6 | Route Helper |

### Development Dependencies

| Package | Version | Constraint | Purpose |
|---------|---------|------------|---------|
| fakerphp/faker | 1.23.x | ^1.23 | Fake Data Generator |
| laravel/pint | 1.24.x | ^1.24 | Code Style Fixer |
| laravel/sail | 1.41.x | ^1.41 | Docker Environment |
| phpunit/phpunit | 11.5.3 | ^11.5.3 | Testing Framework |
| mockery/mockery | 1.6.x | ^1.6 | Mocking Library |
| nunomaduro/collision | 8.6.x | ^8.6 | Error Handler |

## Node.js Dependencies (NPM)

### Production Dependencies

| Package | Version | Constraint | Purpose |
|---------|---------|------------|---------|
| vue | 3.5.29 | ^3.5.29 | Frontend Framework |
| @inertiajs/vue3 | 2.3.17 | ^2.3.17 | Inertia Vue Adapter |
| @heroicons/vue | 2.2.0 | ^2.2.0 | Icon Library |
| @vitejs/plugin-vue | 6.0.4 | ^6.0.4 | Vite Vue Plugin |

### Development Dependencies

| Package | Version | Constraint | Purpose |
|---------|---------|------------|---------|
| vite | 7.0.4 | ^7.0.4 | Build Tool |
| tailwindcss | 4.0.0 | ^4.0.0 | CSS Framework |
| @tailwindcss/vite | 4.0.0 | ^4.0.0 | Tailwind Vite Plugin |
| laravel-vite-plugin | 2.0.0 | ^2.0.0 | Laravel Vite Integration |
| axios | 1.11.0 | ^1.11.0 | HTTP Client |
| concurrently | 9.0.1 | ^9.0.1 | Run Multiple Commands |

## Database Schema Version

- **Migration Version**: 2026_03_03_123702
- **Last Migration**: add_indexes_to_products_table
- **Tables**: 15 tables
  - users
  - shops
  - customers
  - products
  - product_categories
  - orders
  - transactions
  - transaction_items
  - cash_registers
  - discounts
  - reviews
  - shipments
  - notifications
  - password_reset_tokens
  - sessions

## Version Control Files

Proyek ini menyertakan file-file berikut untuk lock versi:

### `.node-version`
```
24.7.0
```
Digunakan oleh: nodenv, fnm

### `.nvmrc`
```
24.7.0
```
Digunakan oleh: nvm (Node Version Manager)

### `.php-version`
```
8.2
```
Digunakan oleh: phpenv, Laravel Herd

### `composer.lock`
Lock file untuk PHP dependencies (auto-generated)

### `package-lock.json`
Lock file untuk Node.js dependencies (auto-generated)

## Compatibility Matrix

### PHP Version Compatibility

| PHP Version | Laravel 12 | Filament 3.3 | Status |
|-------------|------------|--------------|--------|
| 8.2.x | ✅ Full Support | ✅ Full Support | Recommended |
| 8.3.x | ✅ Full Support | ✅ Full Support | Recommended |
| 8.4.x | ⚠️ Partial | ⚠️ Partial | Testing |
| 8.5.x | ⚠️ Deprecations | ❌ Not Supported | Development Only |

### Node.js Version Compatibility

| Node Version | Vite 7 | Vue 3.5 | Status |
|--------------|--------|---------|--------|
| 18.x LTS | ✅ Supported | ✅ Supported | Minimum |
| 20.x LTS | ✅ Full Support | ✅ Full Support | Recommended |
| 22.x LTS | ✅ Full Support | ✅ Full Support | Recommended |
| 24.x | ✅ Supported | ✅ Supported | Latest |

### MySQL Version Compatibility

| MySQL Version | Laravel 12 | Status |
|---------------|------------|--------|
| 5.7.x | ⚠️ Legacy | Not Recommended |
| 8.0.x | ✅ Full Support | Recommended |
| 8.1.x | ✅ Full Support | Latest |

## Installation Commands

### Development Environment

```bash
# Install PHP dependencies (with platform check)
composer install

# Install Node.js dependencies
npm install

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Build assets
npm run build
```

### Production Environment

```bash
# Install PHP dependencies (optimized)
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies (production)
npm ci --production=false

# Build assets (production)
npm run build

# Optimize Laravel
php artisan optimize
```

### For PHP 8.5 (Development Only)

```bash
# Ignore platform requirements
composer install --ignore-platform-reqs
```

## Update Strategy

### Minor Updates (Recommended)

```bash
# Update composer dependencies
composer update --with-dependencies

# Update npm dependencies
npm update

# Test thoroughly before deploying
```

### Major Updates (Caution)

```bash
# Check for breaking changes first
composer outdated
npm outdated

# Update one by one
composer require laravel/framework:^13.0
npm install vue@^4.0.0

# Run tests
php artisan test
npm run test
```

## Known Issues

### PHP 8.5 Deprecation Warnings

**Issue**: PDO constant deprecations
```
Deprecated: Constant PDO::MYSQL_ATTR_SSL_CA is deprecated since 8.5
```

**Workaround**:
1. Use PHP 8.2 or 8.3 for production
2. Or add to `bootstrap/app.php`:
```php
error_reporting(E_ALL & ~E_DEPRECATED);
```

**Permanent Fix**: Updated in `config/database.php` to use `\Pdo\Mysql::ATTR_SSL_CA`

### Filament 3.3 PHP 8.5 Compatibility

**Issue**: Filament 3.3 not fully tested with PHP 8.5

**Solution**: Use PHP 8.2 or 8.3 for production deployment

## Verification Commands

### Check Installed Versions

```bash
# PHP version
php -v

# Node.js version
node -v

# NPM version
npm -v

# Composer version
composer -V

# MySQL version
mysql --version

# Laravel version
php artisan --version

# Check PHP extensions
php -m

# Check composer dependencies
composer show

# Check npm dependencies
npm list --depth=0
```

## Rollback Strategy

### Composer Rollback

```bash
# Restore from composer.lock
composer install

# Or rollback specific package
composer require laravel/framework:12.26.3
```

### NPM Rollback

```bash
# Restore from package-lock.json
npm ci

# Or rollback specific package
npm install vue@3.5.29
```

## Last Updated

- **Date**: 2026-03-03
- **By**: Development Team
- **Reason**: Initial version lock documentation

## Notes

1. Always use `composer.lock` and `package-lock.json` in version control
2. Test thoroughly after any version updates
3. Keep development and production environments as similar as possible
4. Document any version-specific workarounds
5. Review security advisories regularly

## Contact

For version-related issues:
- Email: fatahgilang23@gmail.com
- WhatsApp: 082333058317
