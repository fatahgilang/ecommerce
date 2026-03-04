# 🚀 Railway.app Quick Start Guide

Deploy aplikasi E-Commerce ini ke Railway.app dalam 5 menit!

## Metode 1: Via Dashboard (Paling Mudah) ⭐

### 1. Push ke GitHub

```bash
git add .
git commit -m "Ready for Railway deployment"
git push origin main
```

### 2. Deploy ke Railway

1. Kunjungi [railway.app](https://railway.app)
2. Login dengan GitHub
3. Click **"New Project"**
4. Pilih **"Deploy from GitHub repo"**
5. Pilih repository ini
6. Click **"Deploy Now"**

### 3. Add MySQL Database

1. Click **"+ New"** di project
2. Pilih **"Database"** > **"Add MySQL"**
3. Done! Railway auto-connect ke Laravel

### 4. Set Environment Variables

1. Click service Laravel
2. Go to **"Variables"** tab
3. Add variable:
   ```
   APP_KEY=base64:xxxxx (generate dengan: php artisan key:generate --show)
   ```

### 5. Generate Domain

1. Go to **"Settings"** > **"Domains"**
2. Click **"Generate Domain"**
3. Copy domain: `your-app.railway.app`

### 6. Akses Aplikasi

- **Frontend**: `https://your-app.railway.app`
- **Admin**: `https://your-app.railway.app/admin`
  - Email: `admin@example.com`
  - Password: `password`

**✅ Done! Aplikasi sudah live!**

---

## Metode 2: Via CLI (Untuk Developer)

### 1. Install Railway CLI

```bash
npm install -g @railway/cli
```

### 2. Run Setup Script

```bash
bash scripts/railway-setup.sh
```

Script akan otomatis:
- Login ke Railway
- Create project
- Add MySQL
- Set environment variables
- Deploy aplikasi

### 3. Monitor Deployment

```bash
railway logs --follow
```

**✅ Done!**

---

## Metode 3: One-Click Deploy

Click tombol ini:

[![Deploy on Railway](https://railway.app/button.svg)](https://railway.app/new/template?template=https://github.com/yourusername/ecommerce)

**Note:** Ganti `yourusername` dengan username GitHub Anda

---

## Troubleshooting

### Build Failed?

```bash
# Check logs
railway logs

# Rebuild
railway up --detach
```

### Database Connection Error?

1. Pastikan MySQL service running
2. Check variables: `railway variables`
3. Restart service

### 500 Error?

```bash
# Enable debug temporarily
railway variables set APP_DEBUG=true

# Check logs
railway logs

# Disable debug after fixing
railway variables set APP_DEBUG=false
```

---

## Useful Commands

```bash
# View logs
railway logs

# Follow logs
railway logs --follow

# Open dashboard
railway open

# Check status
railway status

# List variables
railway variables

# Run artisan command
railway run php artisan migrate

# Connect to database
railway connect mysql
```

---

## Cost

**Free Tier:**
- $5 credit per bulan
- Cukup untuk demo dan testing
- Auto-sleep jika tidak digunakan

**Upgrade jika perlu:**
- Hobby: $5/month
- Pro: $20/month

---

## Next Steps

1. ✅ Deploy aplikasi
2. ✅ Test semua fitur
3. ✅ Ganti password default
4. ✅ Share demo URL
5. ✅ Collect feedback

---

## Support

- 📖 Full Guide: [docs/RAILWAY_DEPLOYMENT.md](docs/RAILWAY_DEPLOYMENT.md)
- 🌐 Railway Docs: https://docs.railway.app
- 💬 Discord: https://discord.gg/railway
- 📧 Email: fatahgilang23@gmail.com

---

**Happy Deploying! 🎉**
