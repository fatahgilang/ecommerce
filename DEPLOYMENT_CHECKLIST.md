# ✅ Deployment Checklist

Gunakan checklist ini sebelum deploy ke Railway.app

## Pre-Deployment

### Code Quality
- [ ] Semua fitur sudah di-test
- [ ] Tidak ada error di console browser
- [ ] Tidak ada error di Laravel logs
- [ ] Code sudah di-commit ke Git
- [ ] Branch `main` up to date

### Configuration Files
- [ ] `railway.json` ada dan valid
- [ ] `nixpacks.toml` ada dan valid
- [ ] `Procfile` ada dan valid
- [ ] `.env.example` sudah update
- [ ] `composer.json` dependencies valid
- [ ] `package.json` dependencies valid

### Database
- [ ] Migrations sudah di-test
- [ ] Seeders berjalan dengan baik
- [ ] Indexes sudah ditambahkan
- [ ] Foreign keys sudah benar

### Security
- [ ] `.env` tidak di-commit (ada di .gitignore)
- [ ] Sensitive data tidak hardcoded
- [ ] CORS configuration sudah benar
- [ ] Rate limiting enabled
- [ ] CSRF protection enabled

## Railway Setup

### Account & Project
- [ ] Akun Railway sudah dibuat
- [ ] GitHub connected ke Railway
- [ ] Repository sudah di-push ke GitHub
- [ ] Project Railway sudah dibuat

### Database
- [ ] MySQL service sudah ditambahkan
- [ ] Database credentials auto-injected
- [ ] Connection test berhasil

### Environment Variables
- [ ] `APP_KEY` generated dan di-set
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL` sesuai domain Railway
- [ ] `APP_LOCALE=id`
- [ ] Database variables (auto dari Railway)
- [ ] Session & Cache drivers set

### Domain
- [ ] Railway domain generated
- [ ] `APP_URL` updated dengan domain
- [ ] SSL certificate active (auto)
- [ ] Custom domain configured (optional)

## Deployment

### Build Process
- [ ] Build started successfully
- [ ] Composer install completed
- [ ] NPM install completed
- [ ] Assets built (npm run build)
- [ ] Laravel optimizations run

### Deploy Process
- [ ] Migrations run successfully
- [ ] Seeders run successfully
- [ ] Storage linked
- [ ] Application started
- [ ] Health check passed

### Verification
- [ ] Homepage loads (`/`)
- [ ] Admin panel loads (`/admin`)
- [ ] Login works
- [ ] Database queries work
- [ ] Images load correctly
- [ ] API endpoints work
- [ ] No console errors
- [ ] No 500 errors

## Post-Deployment

### Security
- [ ] Default admin password changed
- [ ] Test user passwords changed
- [ ] `APP_DEBUG=false` confirmed
- [ ] Error pages working (404, 500)
- [ ] HTTPS working

### Performance
- [ ] Page load time acceptable
- [ ] Database queries optimized
- [ ] Cache working
- [ ] Assets loading fast
- [ ] No memory issues

### Monitoring
- [ ] Logs accessible
- [ ] Error tracking setup
- [ ] Uptime monitoring (optional)
- [ ] Backup strategy planned

### Documentation
- [ ] README updated dengan demo URL
- [ ] Credentials documented
- [ ] Known issues documented
- [ ] Support contact updated

## Testing

### Frontend
- [ ] Homepage
- [ ] Product listing
- [ ] Product detail
- [ ] Search functionality
- [ ] Cart functionality
- [ ] Responsive design

### Admin Panel
- [ ] Login
- [ ] Dashboard
- [ ] Product management
- [ ] Order management
- [ ] Transaction management
- [ ] User management
- [ ] Reports

### API
- [ ] GET endpoints
- [ ] POST endpoints
- [ ] Authentication
- [ ] Error handling
- [ ] Rate limiting

## Rollback Plan

- [ ] Previous deployment ID noted
- [ ] Rollback procedure documented
- [ ] Database backup available
- [ ] Contact support if needed

## Communication

- [ ] Demo URL shared
- [ ] Credentials shared (securely)
- [ ] Known limitations communicated
- [ ] Feedback channel established

## Maintenance

- [ ] Update schedule planned
- [ ] Backup schedule set
- [ ] Monitoring alerts configured
- [ ] Support process defined

---

## Quick Commands

```bash
# Check deployment status
railway status

# View logs
railway logs --follow

# Run migrations
railway run php artisan migrate --force

# Clear cache
railway run php artisan optimize:clear

# Restart service
railway restart

# Rollback
# Go to dashboard > Deployments > Select previous > Redeploy
```

---

## Emergency Contacts

- **Railway Support**: https://discord.gg/railway
- **Developer**: fatahgilang23@gmail.com
- **WhatsApp**: 082333058317

---

## Notes

- Railway free tier: $5 credit/month
- Auto-sleep after inactivity
- First request may be slow (cold start)
- Monitor usage in dashboard

---

**Last Updated**: 2026-03-03
**Version**: 1.0.0
