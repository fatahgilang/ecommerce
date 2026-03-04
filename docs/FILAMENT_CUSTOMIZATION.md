# Dokumentasi Kustomisasi Filament Admin Panel

## Daftar Isi
1. [Pengenalan](#pengenalan)
2. [Branding](#branding)
3. [Struktur File](#struktur-file)
4. [Custom Theme](#custom-theme)
5. [Custom Dashboard](#custom-dashboard)
6. [Custom Login Page](#custom-login-page)
7. [Custom Widgets](#custom-widgets)
8. [Konfigurasi Panel](#konfigurasi-panel)
9. [Cara Build Assets](#cara-build-assets)

## Pengenalan

Admin panel menggunakan Filament 3.3 dengan kustomisasi penuh pada theme, layout, dan komponen. Semua kustomisasi dirancang untuk memberikan pengalaman pengguna yang modern dan intuitif.

## Branding

### Logo & Brand Name

**Admin Panel:**
- Brand Name: "Akun Demo" (teks tanpa logo image)
- Konfigurasi: `app/Providers/Filament/AdminPanelProvider.php`

```php
->brandName('Akun Demo')
```

**Frontend E-Commerce:**
- Brand Name: "Akun Demo" dengan styling bold dan warna biru
- Lokasi: `resources/js/Layouts/AppLayout.vue`

```vue
<span class="text-2xl font-bold text-blue-600">Akun Demo</span>
```

### Mengubah Brand Name

**Admin Panel:**
Edit file `app/Providers/Filament/AdminPanelProvider.php`:
```php
->brandName('Nama Brand Anda')
```

**Frontend:**
Edit file `resources/js/Layouts/AppLayout.vue`:
```vue
<span class="text-2xl font-bold text-blue-600">Nama Brand Anda</span>
```

Setelah mengubah, jalankan:
```bash
php artisan optimize:clear
npm run build
```

## Struktur File

```
app/
├── Filament/
│   └── Admin/
│       ├── Pages/
│       │   └── Dashboard.php          # Custom dashboard page
│       ├── Resources/                  # Resource files
│       └── Widgets/
│           └── SalesChart.php         # Custom widgets
│
├── Providers/
│   └── Filament/
│       └── AdminPanelProvider.php     # Panel configuration
│
resources/
├── css/
│   └── filament/
│       └── admin/
│           └── theme.css              # Custom theme CSS
│
└── views/
    └── filament/
        └── admin/
            └── pages/
                ├── dashboard.blade.php    # Dashboard view
                └── auth/
                    └── login.blade.php    # Custom login page
```

## Custom Theme

### File: `resources/css/filament/admin/theme.css`

Theme custom mencakup:

#### 1. Custom Sidebar
```css
.fi-sidebar {
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    border-right: 1px solid rgba(255, 255, 255, 0.1);
}

.fi-sidebar-nav-item:hover {
    background: rgba(59, 130, 246, 0.1);
    transform: translateX(4px);
}
```

**Fitur:**
- Gradient background untuk sidebar
- Hover effect dengan transform
- Active state dengan border kiri
- Smooth transitions

#### 2. Custom Cards
```css
.fi-section {
    border-radius: 12px;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.fi-section:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
```

**Fitur:**
- Rounded corners
- Hover shadow effect
- Smooth transitions

#### 3. Custom Buttons
```css
.fi-btn-primary {
    background: var(--primary-color);
    transition: all 0.2s ease;
}

.fi-btn-primary:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
}
```

**Fitur:**
- Hover lift effect
- Shadow on hover
- Color transitions

#### 4. Custom Forms
```css
.fi-input {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.fi-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
```

**Fitur:**
- Focus ring effect
- Smooth border transitions
- Consistent styling

#### 5. Dark Mode Support
```css
.dark .fi-sidebar {
    background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
}

.dark .fi-input {
    background: #0f172a;
    border-color: #334155;
    color: white;
}
```

**Fitur:**
- Full dark mode support
- Consistent color scheme
- Readable text in dark mode

#### 6. Custom Scrollbar
```css
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
```

**Fitur:**
- Slim scrollbar
- Rounded corners
- Hover effect

## Custom Dashboard

### File: `app/Filament/Admin/Pages/Dashboard.php`

```php
class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.admin.pages.dashboard';
    protected static ?string $title = 'Dashboard';
    
    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 4,
        ];
    }
}
```

### File: `resources/views/filament/admin/pages/dashboard.blade.php`

Dashboard mencakup:

#### 1. Welcome Section
- Greeting dengan nama user
- Tanggal dan waktu real-time
- Gradient background

#### 2. Stats Cards (4 Cards)
- **Penjualan Hari Ini**: Total penjualan dan jumlah transaksi
- **Total Pesanan**: Total pesanan dan pending orders
- **Total Produk**: Total produk dan stok rendah
- **Total Pelanggan**: Total pelanggan dan pelanggan baru

#### 3. Kasir Aktif
- List kasir yang sedang buka
- Nama kasir dan operator
- Total penjualan per kasir
- Waktu buka kasir

#### 4. Transaksi Terbaru
- 5 transaksi terakhir
- Nomor transaksi
- Customer dan kasir
- Total dan status

#### 5. Produk Stok Rendah
- Produk dengan stok < 10
- Nama produk dan harga
- Jumlah stok tersisa
- Alert styling

#### 6. Pesanan Pending
- Pesanan dengan status pending
- Customer dan produk
- Total harga
- Waktu pemesanan

### Responsive Design

Dashboard responsive dengan breakpoints:
- **Mobile (sm)**: 1 kolom
- **Tablet (md)**: 2 kolom
- **Desktop (xl)**: 4 kolom

## Custom Login Page

### File: `resources/views/filament/admin/pages/auth/login.blade.php`

**Fitur:**
- Gradient background (purple to blue)
- Centered layout
- Custom logo dengan icon
- Shadow effects
- Responsive design

**Styling:**
```html
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
```

## Custom Widgets

### Sales Chart Widget

**File:** `app/Filament/Admin/Widgets/SalesChart.php`

```php
class SalesChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan 7 Hari Terakhir';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get sales data for last 7 days
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $labels[] = $date->isoFormat('DD MMM');
            
            $sales = Transaction::whereDate('created_at', $date)
                ->where('status', 'completed')
                ->sum('total_amount');
                
            $data[] = $sales;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan (Rp)',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
```

**Fitur:**
- Line chart dengan gradient fill
- Data 7 hari terakhir
- Format currency Indonesia
- Responsive

### Cara Membuat Widget Baru

```bash
# Create new widget
php artisan make:filament-widget WidgetName --panel=admin

# Create stats widget
php artisan make:filament-widget StatsOverview --stats-overview --panel=admin

# Create chart widget
php artisan make:filament-widget SalesChart --chart --panel=admin
```

## Konfigurasi Panel

### File: `app/Providers/Filament/AdminPanelProvider.php`

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->path('admin')
        ->login()
        ->brandName('E-Commerce Admin')
        ->brandLogo(asset('images/logo.png'))
        ->brandLogoHeight('2.5rem')
        ->favicon(asset('images/favicon.png'))
        ->colors([
            'primary' => Color::Blue,
            'danger' => Color::Red,
            'gray' => Color::Slate,
            'info' => Color::Sky,
            'success' => Color::Green,
            'warning' => Color::Amber,
        ])
        ->font('Inter')
        ->maxContentWidth('full')
        ->sidebarCollapsibleOnDesktop()
        ->sidebarWidth('16rem')
        ->navigationGroups([
            'Manajemen Toko',
            'Manajemen POS',
            'Laporan',
            'Pengaturan',
        ])
        ->databaseNotifications()
        ->databaseNotificationsPolling('30s')
        ->viteTheme('resources/css/filament/admin/theme.css')
        ->spa();
}
```

### Penjelasan Konfigurasi

#### Brand Settings
```php
->brandName('E-Commerce Admin')
->brandLogo(asset('images/logo.png'))
->brandLogoHeight('2.5rem')
->favicon(asset('images/favicon.png'))
```
- Custom brand name
- Logo dan favicon
- Tinggi logo

#### Colors
```php
->colors([
    'primary' => Color::Blue,
    'danger' => Color::Red,
    // ...
])
```
- Primary color: Blue
- Danger: Red
- Success: Green
- Warning: Amber

#### Layout
```php
->font('Inter')
->maxContentWidth('full')
->sidebarCollapsibleOnDesktop()
->sidebarWidth('16rem')
```
- Font: Inter
- Full width content
- Collapsible sidebar
- Sidebar width: 16rem

#### Navigation Groups
```php
->navigationGroups([
    'Manajemen Toko',
    'Manajemen POS',
    'Laporan',
    'Pengaturan',
])
```
- Organized navigation
- Grouped resources
- Indonesian labels

#### Features
```php
->databaseNotifications()
->databaseNotificationsPolling('30s')
->viteTheme('resources/css/filament/admin/theme.css')
->spa()
```
- Database notifications
- 30s polling interval
- Custom Vite theme
- SPA mode enabled

## Cara Build Assets

### Development Mode

```bash
# Install dependencies
npm install

# Run development server
npm run dev
```

### Production Build

```bash
# Build for production
npm run build

# Clear cache
php artisan filament:cache-components
php artisan optimize:clear
```

### Vite Configuration

**File:** `vite.config.js`

```javascript
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/filament/admin/theme.css'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
```

### Tailwind Configuration

**File:** `tailwind.config.js`

```javascript
import preset from './vendor/filament/support/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    // Custom primary colors
                },
            },
            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
            },
        },
    },
}
```

## Customization Tips

### 1. Mengubah Warna Primary

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
->colors([
    'primary' => Color::Purple, // Ganti dengan warna lain
])
```

### 2. Menambah Custom CSS

Edit `resources/css/filament/admin/theme.css`:

```css
/* Your custom styles */
.my-custom-class {
    /* styles */
}
```

### 3. Mengubah Logo

1. Upload logo ke `public/images/logo.png`
2. Update di `AdminPanelProvider.php`:

```php
->brandLogo(asset('images/logo.png'))
```

### 4. Menambah Widget ke Dashboard

Edit `app/Providers/Filament/AdminPanelProvider.php`:

```php
->widgets([
    Widgets\AccountWidget::class,
    Widgets\SalesChart::class, // Add your widget
])
```

### 5. Custom Navigation Icon

Di Resource file:

```php
protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
```

### 6. Custom Page Title

Di Page file:

```php
protected static ?string $title = 'Custom Title';
protected static ?string $navigationLabel = 'Custom Label';
```

## Troubleshooting

### Assets tidak ter-load

```bash
# Clear cache
php artisan optimize:clear

# Rebuild assets
npm run build

# Link storage
php artisan storage:link
```

### Styling tidak muncul

```bash
# Clear Filament cache
php artisan filament:cache-components

# Clear view cache
php artisan view:clear
```

### Widget tidak muncul

1. Check widget registration di `AdminPanelProvider.php`
2. Clear cache: `php artisan optimize:clear`
3. Check widget sort order

### Dark mode issues

Pastikan dark mode classes ada di Tailwind config:

```javascript
content: [
    './app/Filament/**/*.php',
    './resources/views/filament/**/*.blade.php',
    './vendor/filament/**/*.blade.php',
],
```

## Best Practices

1. **Gunakan Vite untuk asset management**
   - Faster build times
   - Hot module replacement
   - Better optimization

2. **Ikuti Filament conventions**
   - Use Filament components
   - Follow naming conventions
   - Use proper namespaces

3. **Optimize untuk production**
   - Minify CSS/JS
   - Cache components
   - Use CDN untuk assets

4. **Responsive design**
   - Test di berbagai device
   - Use Tailwind breakpoints
   - Mobile-first approach

5. **Accessibility**
   - Use semantic HTML
   - Proper ARIA labels
   - Keyboard navigation

## Resources

- [Filament Documentation](https://filamentphp.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Heroicons](https://heroicons.com)
- [Laravel Vite](https://laravel.com/docs/vite)

## Support

Untuk pertanyaan atau issue:
- Email: admin@example.com
- GitHub Issues: https://github.com/fatahgilang/ecommerce/issues
