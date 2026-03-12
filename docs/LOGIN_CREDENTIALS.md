# Login Credentials

## Admin Panel Access
URL: `http://localhost:8000/admin`

## User Accounts

### Administrator
- **Email**: `admin@tokomakmur.com`
- **Password**: `password123`
- **Role**: Full admin access

### Manager
- **Email**: `manager@tokomakmur.com`
- **Password**: `password123`
- **Role**: Manager access

### Kasir (Cashiers)
All cashiers use password: `kasir123`

1. **Siti Nurhaliza**
   - Email: `siti@tokomakmur.com`
   - Password: `kasir123`

2. **Budi Santoso**
   - Email: `budi@tokomakmur.com`
   - Password: `kasir123`

3. **Rina Wati**
   - Email: `rina@tokomakmur.com`
   - Password: `kasir123`

4. **Ahmad Fauzi**
   - Email: `ahmad@tokomakmur.com`
   - Password: `kasir123`

5. **Dewi Sartika**
   - Email: `dewi@tokomakmur.com`
   - Password: `kasir123`

6. **Joko Widodo**
   - Email: `joko@tokomakmur.com`
   - Password: `kasir123`

## Troubleshooting Login Issues

### 1. Clear Browser Cache
- Clear browser cache and cookies
- Try incognito/private browsing mode

### 2. Clear Laravel Cache
```bash
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### 3. Check Database Connection
```bash
php artisan tinker
# Run: \App\Models\User::count()
```

### 4. Verify User Exists
```bash
php artisan tinker
# Run: \App\Models\User::where('email', 'siti@tokomakmur.com')->first()
```

### 5. Test Password
```bash
php artisan tinker
# Run: \Illuminate\Support\Facades\Hash::check('kasir123', \App\Models\User::where('email', 'siti@tokomakmur.com')->first()->password)
```

## Common Issues

1. **Wrong URL**: Make sure you're accessing `/admin` not just `/`
2. **Case Sensitive**: Email addresses are case sensitive
3. **Typos**: Double-check email and password spelling
4. **Session Issues**: Clear browser data and try again
5. **Database Issues**: Run `php artisan migrate:fresh --seed` if needed

## Quick Test
Try logging in with:
- Email: `admin@tokomakmur.com`
- Password: `password123`

If admin works but kasir doesn't, there might be a role/permission issue.