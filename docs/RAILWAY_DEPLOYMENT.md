# Panduan Deploy ke Railway.app

## Tentang Railway.app

Railway.app adalah platform cloud modern yang memudahkan deployment aplikasi. Fitur utama:
- ✅ Free tier dengan $5 credit per bulan
- ✅ Support PHP, Node.js, MySQL, PostgreSQL
- ✅ Auto-deploy dari GitHub
- ✅ SSL certificate gratis
- ✅ Environment variables management
- ✅ Database backup otomatis
- ✅ Monitoring dan logs

## Persiapan

### 1. Buat Akun Railway

1. Kunjungi [railway.app](https://railway.app)
2. Sign up dengan GitHub account
3. Verifikasi email Anda
4. Anda akan mendapat $5 credit gratis per bulan

### 2. Install Railway CLI (Optional)

```bash
# Install via NPM
npm install -g @railway/cli

# Atau via Homebrew (Mac)
brew install railway

# Login
railway login
```

## Deployment via Dashboard (Recommended untuk Pemula)

### Step 1: Push ke GitHub

```bash
# Initialize git (jika belum)
git init

# Add all files
git add .

# Commit
git commit -m "Initial commit for Railway deployment"

# Create repository di GitHub, lalu:
git remote add origin https://github.com/username/ecommerce.git
git branch -M main
git push -u origin main
```

### Step 2: Create New Project di Railway

1. Login ke [railway.app/dashboard](https://railway.app/dashboard)
2. Click **"New Project"**
3. Pilih **"Deploy from GitHub repo"**
4. Authorize Railway untuk akses GitHub
5. Pilih repository **ecommerce**
6. Railway akan otomatis detect Laravel project

### Step 3: Add MySQL Database

1. Di project dashboard, click **"+ New"**
2. Pilih **"Database"**
3. Pilih **"Add MySQL"**
4. Railway akan create database dan provide credentials

### Step 4: Configure Environment Variables

1. Click pada service Laravel Anda
2. Go to **"Variables"** tab
3. Click **"+ New Variable"**
4. Add variables berikut:

```env
APP_NAME=E-Commerce Demo
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

APP_LOCALE=id
APP_FALLBACK_LOCALE=id

# Railway akan auto-inject database credentials
# Tapi Anda bisa override jika perlu
DB_CONNECTION=mysql

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Log
LOG_CHANNEL=stack
LOG_LEVEL=error
```

**Note:** Railway akan otomatis inject variables berikut:
- `DATABASE_URL`
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Step 5: Generate APP_KEY

1. Di Variables tab, click **"+ New Variable"**
2. Name: `APP_KEY`
3. Value: Generate dengan command:
   ```bash
   php artisan key:generate --show
   ```
4. Copy output dan paste sebagai value

### Step 6: Deploy

1. Railway akan otomatis deploy setelah push ke GitHub
2. Atau click **"Deploy"** button di dashboard
3. Monitor deployment di **"Deployments"** tab
4. Tunggu hingga status **"Success"**

### Step 7: Setup Domain

1. Go to **"Settings"** tab
2. Scroll ke **"Domains"**
3. Click **"Generate Domain"**
4. Railway akan provide domain: `your-app.railway.app`
5. Update `APP_URL` di environment variables dengan domain ini

### Step 8: Run Migrations & Seeders

Railway akan otomatis run migrations dan seeders saat deploy (sudah dikonfigurasi di `Procfile`).

Jika perlu manual:
1. Go to service Laravel
2. Click **"..."** menu
3. Pilih **"Run Command"**
4. Run:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

## Deployment via CLI

### Step 1: Link Project

```bash
# Di root project
railway link

# Atau create new project
railway init
```

### Step 2: Add MySQL

```bash
railway add mysql
```

### Step 3: Set Environment Variables

```bash
# Set APP_KEY
railway variables set APP_KEY=$(php artisan key:generate --show)

# Set other variables
railway variables set APP_ENV=production
railway variables set APP_DEBUG=false
railway variables set APP_LOCALE=id
```

### Step 4: Deploy

```bash
# Deploy
railway up

# Atau dengan auto-deploy dari GitHub
railway link
git push
```

### Step 5: Run Commands

```bash
# Run migrations
railway run php artisan migrate --force

# Run seeders
railway run php artisan db:seed --force

# Check logs
railway logs
```

## Konfigurasi File

### railway.json

File ini mengkonfigurasi build dan deploy process:

```json
{
  "$schema": "https://railway.app/railway.schema.json",
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "composer install --no-dev --optimize-autoloader && npm ci && npm run build && php artisan config:cache && php artisan route:cache && php artisan view:cache"
  },
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT",
    "restartPolicyType": "ON_FAILURE",
    "restartPolicyMaxRetries": 10
  }
}
```

### nixpacks.toml

File ini mengkonfigurasi build environment:

```toml
[phases.setup]
nixPkgs = ['php82', 'php82Extensions.pdo', 'php82Extensions.pdo_mysql', 'php82Extensions.mbstring', 'php82Extensions.xml', 'php82Extensions.curl', 'php82Extensions.zip', 'php82Extensions.gd', 'php82Extensions.bcmath', 'nodejs-20_x']

[phases.install]
cmds = [
  'composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist',
  'npm ci'
]

[phases.build]
cmds = [
  'npm run build',
  'php artisan config:cache',
  'php artisan route:cache',
  'php artisan view:cache'
]

[start]
cmd = 'php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT'
```

### Procfile

Backup configuration jika nixpacks tidak digunakan:

```
web: php artisan migrate --force && php artisan db:seed --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT
```

## Akses Aplikasi

Setelah deployment berhasil:

### Frontend E-Commerce
```
https://your-app.railway.app
```

### Admin Panel
```
https://your-app.railway.app/admin
```

**Default Login:**
- Email: `admin@example.com`
- Password: `password`

**Note:** Segera ganti password setelah login pertama!

## Monitoring & Maintenance

### View Logs

**Via Dashboard:**
1. Go to service
2. Click **"Deployments"** tab
3. Click deployment
4. View logs

**Via CLI:**
```bash
railway logs
railway logs --follow
```

### Database Management

**Via Railway Dashboard:**
1. Click MySQL service
2. Go to **"Data"** tab
3. View tables dan data

**Via CLI:**
```bash
# Connect to database
railway connect mysql

# Or get connection string
railway variables
```

### Backup Database

```bash
# Export database
railway run mysqldump -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE > backup.sql

# Import database
railway run mysql -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE < backup.sql
```

### Update Application

```bash
# Push changes to GitHub
git add .
git commit -m "Update features"
git push

# Railway will auto-deploy
```

### Rollback Deployment

1. Go to **"Deployments"** tab
2. Find previous successful deployment
3. Click **"..."** menu
4. Click **"Redeploy"**

## Troubleshooting

### Build Failed

**Problem:** Composer install fails

**Solution:**
```bash
# Check composer.json
# Ensure all dependencies are compatible with PHP 8.2

# Or add to railway.json buildCommand:
composer install --no-dev --optimize-autoloader --ignore-platform-reqs
```

### Migration Failed

**Problem:** Database connection error

**Solution:**
1. Check database service is running
2. Verify environment variables
3. Check `config/database.php` configuration

### 500 Internal Server Error

**Problem:** Application error

**Solution:**
1. Check logs: `railway logs`
2. Enable debug temporarily: `APP_DEBUG=true`
3. Check storage permissions
4. Clear cache: `railway run php artisan optimize:clear`

### Assets Not Loading

**Problem:** CSS/JS 404 errors

**Solution:**
1. Ensure `npm run build` runs in buildCommand
2. Check `public/build` directory exists
3. Verify `APP_URL` is correct

### Database Seeder Runs Every Deploy

**Problem:** Data duplicated

**Solution:**
Edit `Procfile` or `nixpacks.toml` start command:
```bash
# Remove db:seed if you don't want to seed every time
php artisan migrate --force && php artisan storage:link && php artisan serve --host=0.0.0.0 --port=$PORT
```

## Cost Estimation

### Free Tier
- $5 credit per bulan
- Cukup untuk:
  - 1 web service (Laravel)
  - 1 MySQL database
  - Traffic moderate

### Usage Tips
1. Set `APP_DEBUG=false` untuk production
2. Enable cache (config, route, view)
3. Optimize images
4. Use CDN untuk static assets (optional)

### Upgrade Options
Jika credit habis:
- **Hobby Plan**: $5/month
- **Pro Plan**: $20/month
- **Team Plan**: Custom pricing

## Security Checklist

- [ ] `APP_DEBUG=false` di production
- [ ] `APP_ENV=production`
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials secure
- [ ] Change default admin password
- [ ] Enable HTTPS (auto by Railway)
- [ ] Set proper CORS headers
- [ ] Regular backups
- [ ] Monitor logs for suspicious activity

## Performance Optimization

### Laravel Optimization

```bash
# Run on every deploy (already in config)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Database Optimization

```bash
# Add indexes (already done)
# See: database/migrations/2026_03_03_123702_add_indexes_to_products_table.php

# Optimize tables periodically
railway run php artisan tinker
>>> DB::statement('OPTIMIZE TABLE products, orders, transactions');
```

### Caching Strategy

```env
# Use database cache for simplicity
CACHE_STORE=database
SESSION_DRIVER=database

# Or upgrade to Redis for better performance
CACHE_STORE=redis
SESSION_DRIVER=redis
```

## Custom Domain (Optional)

### Add Custom Domain

1. Go to **"Settings"** > **"Domains"**
2. Click **"Custom Domain"**
3. Enter your domain: `demo.yourdomain.com`
4. Add CNAME record di DNS provider:
   ```
   CNAME demo your-app.railway.app
   ```
5. Wait for DNS propagation (5-30 minutes)
6. Railway will auto-provision SSL

## Support & Resources

- **Railway Docs**: https://docs.railway.app
- **Railway Discord**: https://discord.gg/railway
- **Railway Status**: https://status.railway.app
- **Pricing**: https://railway.app/pricing

## Demo Credentials

Setelah deploy, share credentials ini untuk demo:

### Admin Panel
- URL: `https://your-app.railway.app/admin`
- Email: `admin@example.com`
- Password: `password`

### Test Customer
- Email: `customer@example.com`
- Password: `password`

### Test Shop Owner
- Email: `shop@example.com`
- Password: `password`

**⚠️ Important:** Ganti semua password default setelah deployment!

## Next Steps

1. ✅ Deploy aplikasi ke Railway
2. ✅ Test semua fitur
3. ✅ Ganti password default
4. ✅ Setup monitoring
5. ✅ Share demo URL
6. ✅ Collect feedback
7. ✅ Iterate and improve

## Conclusion

Railway.app adalah pilihan terbaik untuk demo aplikasi Laravel karena:
- Setup mudah dan cepat
- Free tier yang generous
- Auto-deploy dari GitHub
- Built-in database
- SSL gratis
- Monitoring dan logs

Selamat mencoba! 🚀
