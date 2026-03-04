# Panduan Pemecahan Masalah (Troubleshooting)

## Error: Route [login] not defined

### Deskripsi Masalah
Error `Symfony\Component\Routing\Exception\RouteNotFoundException: Route [login] not defined` terjadi ketika Laravel mencari rute login yang belum didefinisikan. Ini umum terjadi saat menggunakan Filament tanpa Laravel Breeze atau Jetstream.

### Penyebab
1. Rute `login` tidak didefinisikan dalam `routes/web.php`
2. Model User belum mengimplementasikan interface `FilamentUser`
3. Konfigurasi Filament panel belum lengkap

### Solusi yang Telah Diterapkan

#### 1. Menambahkan Rute Autentikasi di `routes/web.php`
```php
<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Redirect authentication routes to Filament admin
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/register', function () {
    return redirect('/admin/register');
})->name('register');

Route::post('/logout', function () {
    return redirect('/admin/logout');
})->name('logout');
```

#### 2. Memperbarui Model User (`app/Models/User.php`)
```php
<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Allow all users to access the admin panel (suitable for development)
        // In production, you might want to add role-based access control
        return true;
    }
}
```

#### 3. Memperbarui Konfigurasi Filament Panel (`app/Providers/Filament/AdminPanelProvider.php`)
```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->id('admin')
        ->path('admin')
        ->login() // Menambahkan ini untuk mengaktifkan halaman login
        ->colors([
            'primary' => Color::Amber,
        ])
        // ... konfigurasi lainnya
}
```

### Langkah Verifikasi

#### 1. Bersihkan Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 2. Periksa Rute yang Terdaftar
```bash
php artisan route:list --name=login
```

Output yang diharapkan:
```
GET|HEAD       admin/login filament.admin.auth.login
GET|HEAD       login ................... login
```

#### 3. Test Aplikasi
```bash
php artisan serve
```

Akses:
- **Halaman Utama**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin (akan redirect ke login)
- **Login Direct**: http://localhost:8000/login (akan redirect ke admin login)

### Kredensial Login Default
- **Email**: admin@example.com
- **Password**: password

### Masalah Umum Lainnya

#### Error: User sudah ada saat seeding
```bash
# Error ini normal jika user admin sudah dibuat sebelumnya
php artisan db:seed --class=AdminUserSeeder
```

#### Error: Migrasi belum dijalankan
```bash
php artisan migrate
```

#### Error: Storage link belum dibuat
```bash
php artisan storage:link
```

### Tips Pengembangan

#### 1. Kontrol Akses Produksi
Untuk produksi, ubah method `canAccessPanel()` di model User:
```php
public function canAccessPanel(Panel $panel): bool
{
    // Contoh: hanya admin yang bisa akses
    return $this->hasRole('admin');
    
    // Atau berdasarkan email
    return in_array($this->email, [
        'admin@example.com',
        'manager@example.com',
    ]);
}
```

#### 2. Kustomisasi Halaman Login
Buat custom login page jika diperlukan:
```php
// Di AdminPanelProvider.php
->login(\App\Filament\Pages\Auth\Login::class)
```

#### 3. Multi-Panel Setup
Untuk setup multi-panel (admin, user, dll):
```php
// Buat provider terpisah untuk setiap panel
// AdminPanelProvider.php untuk admin
// UserPanelProvider.php untuk user panel
```

### Debugging Tambahan

#### 1. Periksa Log Error
```bash
tail -f storage/logs/laravel.log
```

#### 2. Debug Mode
Pastikan `APP_DEBUG=true` di file `.env` untuk development.

#### 3. Periksa Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### Kontak Support
Jika masalah masih berlanjut, periksa:
1. Versi PHP (minimal 8.2)
2. Versi Laravel (12.0)
3. Versi Filament (3.3)
4. Konfigurasi database di `.env`

---

**Catatan**: Panduan ini mengatasi error spesifik "Route [login] not defined" yang umum terjadi pada setup Filament baru.