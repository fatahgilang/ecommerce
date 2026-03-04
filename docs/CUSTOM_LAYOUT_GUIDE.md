# Panduan Custom Layout Filament

## Ringkasan Perubahan

Sistem admin panel telah dikustomisasi dengan fitur-fitur berikut:

### ✅ Yang Sudah Dikustomisasi

1. **Custom Theme CSS** (`resources/css/filament/admin/theme.css`)
   - Sidebar dengan gradient background
   - Hover effects pada navigation items
   - Custom card styling dengan shadow
   - Custom button dengan lift effect
   - Custom form inputs dengan focus ring
   - Dark mode support
   - Custom scrollbar
   - Responsive design

2. **Custom Dashboard** (`resources/views/filament/admin/pages/dashboard.blade.php`)
   - Welcome section dengan greeting
   - 4 Stats cards (Penjualan, Pesanan, Produk, Pelanggan)
   - Kasir aktif list
   - Transaksi terbaru
   - Produk stok rendah
   - Pesanan pending
   - Fully responsive

3. **Custom Login Page** (`resources/views/filament/admin/pages/auth/login.blade.php`)
   - Gradient background
   - Centered layout
   - Custom logo
   - Modern design

4. **Custom Widgets**
   - SalesChart: Grafik penjualan 7 hari terakhir

5. **Panel Configuration** (`app/Providers/Filament/AdminPanelProvider.php`)
   - Brand name dan logo
   - Custom colors (Blue primary)
   - Inter font
   - Collapsible sidebar
   - Navigation groups
   - Database notifications
   - SPA mode

## Cara Menggunakan

### 1. Build Assets

```bash
# Development
npm run dev

# Production
npm run build
```

### 2. Clear Cache

```bash
php artisan optimize:clear
php artisan filament:cache-components
```

### 3. Akses Admin Panel

```
URL: http://localhost:8000/admin
```

### 4. Login Credentials

Gunakan credentials dari UserSeeder:
- Email: admin@tokomakmur.com
- Password: password123

## Struktur File

```
├── app/
│   ├── Filament/Admin/
│   │   ├── Pages/
│   │   │   └── Dashboard.php
│   │   └── Widgets/
│   │       └── SalesChart.php
│   └── Providers/Filament/
│       └── AdminPanelProvider.php
│
├── resources/
│   ├── css/filament/admin/
│   │   └── theme.css
│   └── views/filament/admin/
│       └── pages/
│           ├── dashboard.blade.php
│           └── auth/
│               └── login.blade.php
│
├── public/build/
│   └── assets/
│       ├── theme-*.css
│       └── app-*.js
│
└── docs/
    ├── FILAMENT_CUSTOMIZATION.md
    └── CUSTOM_LAYOUT_GUIDE.md
```

## Fitur Dashboard

### Stats Cards
- **Penjualan Hari Ini**: Real-time sales data
- **Total Pesanan**: Order count dengan pending indicator
- **Total Produk**: Product count dengan low stock alert
- **Total Pelanggan**: Customer count dengan new customers

### Kasir Aktif
- List kasir yang sedang buka
- Total penjualan per kasir
- Nama operator
- Waktu buka

### Transaksi Terbaru
- 5 transaksi terakhir
- Status badge (completed, pending, cancelled)
- Customer dan kasir info
- Total amount

### Produk Stok Rendah
- Alert untuk produk dengan stok < 10
- Nama produk dan harga
- Jumlah stok tersisa
- Red alert styling

### Pesanan Pending
- Pesanan yang menunggu proses
- Customer dan produk info
- Total harga
- Waktu pemesanan

## Customization

### Mengubah Warna Primary

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
->colors([
    'primary' => Color::Purple, // Ganti warna
])
```

### Menambah Widget

1. Buat widget baru:
```bash
php artisan make:filament-widget MyWidget --panel=admin
```

2. Register di `AdminPanelProvider.php`:
```php
->widgets([
    Widgets\AccountWidget::class,
    Widgets\MyWidget::class,
])
```

### Mengubah Logo

1. Upload logo ke `public/images/logo.png`
2. Update di `AdminPanelProvider.php`:
```php
->brandLogo(asset('images/logo.png'))
```

### Custom CSS

Edit `resources/css/filament/admin/theme.css`:

```css
/* Your custom styles */
.my-custom-class {
    /* styles */
}
```

Kemudian rebuild:
```bash
npm run build
```

## Tips

1. **Selalu clear cache** setelah perubahan:
   ```bash
   php artisan optimize:clear
   ```

2. **Gunakan npm run dev** untuk development:
   ```bash
   npm run dev
   ```

3. **Build untuk production**:
   ```bash
   npm run build
   ```

4. **Check responsive design** di berbagai device

5. **Test dark mode** dengan toggle di admin panel

## Troubleshooting

### Assets tidak ter-load
```bash
php artisan optimize:clear
npm run build
php artisan storage:link
```

### Styling tidak muncul
```bash
php artisan filament:cache-components
php artisan view:clear
npm run build
```

### Widget tidak muncul
1. Check registration di `AdminPanelProvider.php`
2. Clear cache
3. Check widget sort order

## Next Steps

1. ✅ Custom theme CSS - DONE
2. ✅ Custom dashboard - DONE
3. ✅ Custom login page - DONE
4. ✅ Custom widgets - DONE
5. ✅ Panel configuration - DONE
6. ⏳ Add more widgets (optional)
7. ⏳ Custom resource pages (optional)
8. ⏳ Custom actions (optional)

## Resources

- [Filament Docs](https://filamentphp.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Heroicons](https://heroicons.com)

## Support

Untuk pertanyaan:
- Lihat dokumentasi lengkap di `docs/FILAMENT_CUSTOMIZATION.md`
- Check troubleshooting section
- GitHub Issues
