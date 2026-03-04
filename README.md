# Dokumentasi Sistem E-Commerce

[![Laravel](https://img.shields.io/badge/Laravel-12.26-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat&logo=php)](https://php.net)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.5-4FC08D?style=flat&logo=vue.js)](https://vuejs.org)
[![Filament](https://img.shields.io/badge/Filament-3.3-FDAE4B?style=flat)](https://filamentphp.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)](https://mysql.com)
[![Deploy on Railway](https://img.shields.io/badge/Deploy-Railway-0B0D0E?style=flat&logo=railway)](https://railway.app)

## Demo Online

🚀 **Live Demo**: [Coming Soon - Deploy ke Railway.app](docs/RAILWAY_DEPLOYMENT.md)

**Admin Panel**: `/admin`
- Email: `admin@example.com`
- Password: `password`

**Frontend E-Commerce**: `/`

## Daftar Isi
1. [Gambaran Umum](#gambaran-umum)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Instalasi dan Pengaturan](#instalasi-dan-pengaturan)
4. [Struktur Basis Data](#struktur-basis-data)
5. [Titik Akhir API](#titik-akhir-api)
6. [Panel Admin](#panel-admin)
7. [Fitur Utama](#fitur-utama)
8. [Konfigurasi](#konfigurasi)
9. [Pengujian](#pengujian)
10. [Penyebaran](#penyebaran)

## Gambaran Umum

Sistem E-Commerce ini adalah aplikasi web modern yang dibangun menggunakan Laravel 12 dengan panel admin Filament. Sistem ini menyediakan platform lengkap untuk mengelola toko online dengan fitur-fitur seperti manajemen produk, pesanan, pelanggan, dan pengiriman.

### Fitur Utama
- ✅ Manajemen produk dan kategori
- ✅ Sistem multi-toko (marketplace)
- ✅ Manajemen pesanan dan pembayaran
- ✅ Sistem review dan rating produk
- ✅ Tracking pengiriman
- ✅ Panel admin yang user-friendly
- ✅ RESTful API untuk integrasi mobile/frontend
- ✅ Sistem pencarian dan filter
- ✅ Dashboard statistik
- ✅ **Sistem Kasir/POS Lengkap**
- ✅ **Manajemen Cash Register**
- ✅ **Transaksi Penjualan Real-time**
- ✅ **Sistem Diskon dan Promo**
- ✅ **Laporan Penjualan Komprehensif**
- ✅ **Split Payment Support**
- ✅ **Checkout dengan Metode Pembayaran**
- ✅ **Transfer Bank & E-Wallet**
- ✅ **Guest Checkout (Tanpa Registrasi)**

## Teknologi yang Digunakan

### Persyaratan Sistem

| Software | Versi Minimum | Versi Direkomendasikan |
|----------|---------------|------------------------|
| PHP | 8.2.0 | 8.2.x atau 8.3.x |
| Node.js | 20.0.0 | 20.x LTS atau 22.x LTS |
| NPM | 10.0.0 | 10.x |
| Composer | 2.5.0 | 2.9.x |
| MySQL | 8.0.0 | 8.0.27+ |

**⚠️ Catatan Penting:**
- Proyek ini dikembangkan dengan PHP 8.5, namun untuk production gunakan PHP 8.2 atau 8.3
- File `.node-version`, `.nvmrc`, dan `.php-version` tersedia untuk lock versi
- Lihat `VERSION_LOCK.md` untuk detail lengkap semua versi dependency

### Backend
- **Framework**: Laravel 12.26.3
- **PHP**: 8.2+ (Development: 8.5.0)
- **Database**: MySQL 8.0.27
- **Authentication**: Laravel Sanctum 4.2
- **Admin Panel**: Filament 3.3
- **Bahasa**: Indonesia (ID)

### Frontend
- **Framework**: Vue.js 3.5.29 + Inertia.js 2.3.17
- **Build Tool**: Vite 7.0.4
- **CSS Framework**: Tailwind CSS 4.0
- **Icons**: Heroicons 2.2.0
- **HTTP Client**: Axios 1.11.0

### Development Tools
- **Testing**: PHPUnit 11.5.3
- **Code Style**: Laravel Pint
- **Container**: Laravel Sail (Docker)
- **Queue**: Database-driven
- **Cache**: Database-driven
- **Locale**: Indonesia (id_ID)

## Instalasi dan Pengaturan

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js dan NPM
- MySQL 8.0+
- Git

### Langkah Instalasi

1. **Kloning Repositori**
```bash
git clone https://github.com/fatahgilang/ecommerce.git
cd ecommerce
```

2. **Instalasi Dependensi**
```bash
# Instalasi dependensi PHP
composer install

# Instalasi dependensi Node.js
npm install
```

3. **Konfigurasi Lingkungan**
```bash
# Salin berkas lingkungan
cp .env.example .env

# Buat kunci aplikasi
php artisan key:generate
```

4. **Konfigurasi Basis Data**
Edit berkas `.env` dan sesuaikan konfigurasi basis data:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=kata_sandi_anda
```

5. **Migrasi Basis Data**
```bash
# Jalankan migrasi
php artisan migrate

# Isi data admin
php artisan db:seed --class=AdminUserSeeder
```

6. **Pengaturan Penyimpanan**
```bash
# Tautkan penyimpanan untuk unggahan berkas
php artisan storage:link
```

7. **Menjalankan Aplikasi**
```bash
# Mode pengembangan (menjalankan server, antrian, dan vite bersamaan)
composer dev

# Atau jalankan secara terpisah:
php artisan serve
php artisan queue:work
npm run dev
```

### Akses Aplikasi
- **Aplikasi Web**: http://localhost:8000
- **Panel Admin**: http://localhost:8000/admin
- **URL Dasar API**: http://localhost:8000/api/v1

### Kredensial Admin Bawaan
- **Surel**: admin@example.com
- **Kata Sandi**: password

## Struktur Basis Data

### Tabel Utama

#### 1. Users (Admin/Staf)
```sql
- id (Kunci Utama)
- name (nama)
- email (Unik)
- password (kata_sandi)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 2. Customers (Pelanggan)
```sql
- id (Kunci Utama)
- name (nama)
- address (alamat - Teks)
- email (Unik)
- date_of_birth (tanggal_lahir - Tanggal)
- phone (telepon)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 3. Shops (Toko)
```sql
- id (Kunci Utama)
- shop_name (nama_toko)
- description (deskripsi - Teks)
- address (alamat - Teks)
- phone (telepon)
- email
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 4. Products (Produk)
```sql
- id (Kunci Utama)
- shop_id (Kunci Asing -> shops.id)
- product_name (nama_produk)
- product_description (deskripsi_produk - Teks)
- product_price (harga_produk - Desimal 10,2)
- price_per_unit (harga_per_unit - Desimal 10,2)
- unit (satuan - Bawaan: 'pcs')
- stock (stok - Integer, Bawaan: 0)
- image (gambar - Nullable)
- weight (berat - Double)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 5. Product_Categories (Kategori Produk)
```sql
- id (Kunci Utama)
- product_id (Kunci Asing -> products.id)
- category_name (nama_kategori)
- product_description (deskripsi_produk - Teks)
- unit (satuan)
- price_per_unit (harga_per_unit - Desimal 10,2)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 6. Orders (Pesanan)
```sql
- id (Kunci Utama)
- customer_id (Kunci Asing -> customers.id)
- product_id (Kunci Asing -> products.id)
- product_quantity (jumlah_produk - Integer)
- total_price (total_harga - Desimal 10,2)
- payment_method (metode_pembayaran - Enum: transfer, cod, ewallet, credit_card)
- status (Enum: pending, processing, completed, cancelled)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 7. Reviews (Ulasan)
```sql
- id (Kunci Utama)
- product_id (Kunci Asing -> products.id)
- customer_id (Kunci Asing -> customers.id)
- review (ulasan - Teks)
- rating (penilaian - Integer 1-5)
- is_verified_purchase (pembelian_terverifikasi - Boolean)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 9. Cash_Registers (Kasir)
```sql
- id (Kunci Utama)
- register_name (nama_kasir)
- user_id (Kunci Asing -> users.id)
- opening_balance (saldo_awal - Desimal 15,2)
- closing_balance (saldo_akhir - Desimal 15,2)
- total_sales (total_penjualan - Desimal 15,2)
- total_cash (total_tunai - Desimal 15,2)
- total_card (total_kartu - Desimal 15,2)
- total_ewallet (total_ewallet - Desimal 15,2)
- status (Enum: open, closed)
- opened_at (dibuka_pada - Timestamp)
- closed_at (ditutup_pada - Timestamp)
- notes (catatan - Teks)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 10. Transactions (Transaksi Kasir)
```sql
- id (Kunci Utama)
- transaction_number (nomor_transaksi - Unik)
- cash_register_id (Kunci Asing -> cash_registers.id)
- customer_id (Kunci Asing -> customers.id - Nullable)
- user_id (Kunci Asing -> users.id)
- subtotal (subtotal - Desimal 15,2)
- tax_amount (jumlah_pajak - Desimal 15,2)
- discount_amount (jumlah_diskon - Desimal 15,2)
- total_amount (total_jumlah - Desimal 15,2)
- payment_method (Enum: cash, card, ewallet, transfer, split)
- paid_amount (jumlah_dibayar - Desimal 15,2)
- change_amount (jumlah_kembalian - Desimal 15,2)
- status (Enum: pending, completed, cancelled, refunded)
- notes (catatan - Teks)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 11. Transaction_Items (Item Transaksi)
```sql
- id (Kunci Utama)
- transaction_id (Kunci Asing -> transactions.id)
- product_id (Kunci Asing -> products.id)
- quantity (jumlah - Integer)
- unit_price (harga_satuan - Desimal 15,2)
- discount_amount (jumlah_diskon - Desimal 15,2)
- total_price (total_harga - Desimal 15,2)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 12. Discounts (Diskon)
```sql
- id (Kunci Utama)
- name (nama)
- code (kode - Unik, Nullable)
- type (Enum: percentage, fixed_amount)
- value (nilai - Desimal 15,2)
- minimum_amount (jumlah_minimum - Desimal 15,2)
- maximum_discount (diskon_maksimum - Desimal 15,2)
- start_date (tanggal_mulai - Date)
- end_date (tanggal_berakhir - Date)
- usage_limit (batas_penggunaan - Integer)
- used_count (jumlah_digunakan - Integer)
- is_active (aktif - Boolean)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

#### 13. Payment_Splits (Pembayaran Terpisah)
```sql
- id (Kunci Utama)
- transaction_id (Kunci Asing -> transactions.id)
- payment_method (Enum: cash, card, ewallet, transfer)
- amount (jumlah - Desimal 15,2)
- reference_number (nomor_referensi - Nullable)
- created_at, updated_at (dibuat_pada, diperbarui_pada)
```

### Relasi Basis Data
- **Shop** → **Products** (Satu ke Banyak)
- **Product** → **Categories** (Satu ke Banyak)
- **Product** → **Orders** (Satu ke Banyak)
- **Product** → **Reviews** (Satu ke Banyak)
- **Product** → **TransactionItems** (Satu ke Banyak)
- **Customer** → **Orders** (Satu ke Banyak)
- **Customer** → **Reviews** (Satu ke Banyak)
- **Customer** → **Transactions** (Satu ke Banyak)
- **User** → **CashRegisters** (Satu ke Banyak)
- **User** → **Transactions** (Satu ke Banyak)
- **CashRegister** → **Transactions** (Satu ke Banyak)
- **Transaction** → **TransactionItems** (Satu ke Banyak)
- **Transaction** → **PaymentSplits** (Satu ke Banyak)
- **Order** → **Shipment** (Satu ke Satu)

## Titik Akhir API

URL Dasar: `/api/v1`

### Titik Akhir Produk

#### GET /products
Mendapatkan daftar semua produk dengan paginasi
```json
Respons:
{
  "data": [
    {
      "id": 1,
      "product_name": "Nama Produk",
      "product_price": "100000.00",
      "stock": 50,
      "image": "url_gambar",
      "shop": {
        "shop_name": "Nama Toko"
      }
    }
  ],
  "meta": {
    "current_page": 1,
    "per_page": 12,
    "total": 100
  }
}
```

#### GET /products/featured
Mendapatkan produk unggulan

#### GET /products/best-sellers
Mendapatkan produk terlaris

#### GET /products/{id}
Mendapatkan detail produk beserta ulasan
```json
Respons:
{
  "id": 1,
  "product_name": "Nama Produk",
  "product_description": "Deskripsi produk",
  "product_price": "100000.00",
  "stock": 50,
  "shop": {
    "shop_name": "Nama Toko",
    "phone": "08123456789"
  },
  "reviews": [
    {
      "rating": 5,
      "review": "Produk bagus",
      "customer": {
        "name": "Nama Pelanggan"
      }
    }
  ]
}
```

#### POST /products/{id}/reviews
Menambahkan ulasan produk
```json
Permintaan:
{
  "customer_id": 1,
  "review": "Produk sangat bagus",
  "rating": 5
}
```

### Titik Akhir Pesanan

#### GET /orders?customer_id={id}
Mendapatkan daftar pesanan pelanggan
```json
Respons:
{
  "data": [
    {
      "id": 1,
      "order_number": "ORD-001",
      "product": {
        "name": "Nama Produk",
        "price": "100000.00"
      },
      "quantity": 2,
      "total_price": "200000.00",
      "status": "pending",
      "created_at": "01 Jan 2025 10:00"
    }
  ]
}
```

#### POST /orders
Membuat pesanan baru
```json
Permintaan:
{
  "customer_id": 1,
  "product_id": 1,
  "product_quantity": 2,
  "payment_method": "transfer",
  "shipping_address": "Alamat lengkap",
  "recipient_name": "Nama penerima",
  "recipient_phone": "08123456789",
  "shipment_type": "regular",
  "delivery_cost": 15000
}

Respons:
{
  "message": "Pesanan berhasil dibuat",
  "order": {
    "id": 1,
    "order_number": "ORD-001",
    "total_price": "215000.00",
    "status": "pending"
  }
}
```

#### POST /orders/{id}/cancel
Membatalkan pesanan
```json
Permintaan:
{
  "reason": "Alasan pembatalan minimal 10 karakter"
}
```

### Titik Akhir Pelanggan

#### GET /customers
Mendapatkan daftar pelanggan

#### POST /customers
Membuat pelanggan baru
```json
Permintaan:
{
  "name": "Nama Pelanggan",
  "email": "pelanggan@email.com",
  "address": "Alamat lengkap",
  "phone": "08123456789",
  "date_of_birth": "1990-01-01"
}
```

#### GET /customers/{id}
Mendapatkan detail pelanggan

#### PUT /customers/{id}
Memperbarui data pelanggan

#### DELETE /customers/{id}
Menghapus pelanggan

### Titik Akhir Utilitas

#### GET /search?q={query}
Pencarian global produk dan toko
```json
Respons:
{
  "products": [...],
  "shops": [...]
}
```

#### GET /home
Data untuk halaman utama
```json
Respons:
{
  "featured_products": [...],
  "best_sellers": [...],
  "new_arrivals": [...],
  "categories": [...]
}
```

#### GET /health
Titik akhir pemeriksaan kesehatan
```json
Respons:
{
  "status": "ok",
  "timestamp": "2025-01-01 10:00:00"
}
```

## Panel Admin

Panel admin menggunakan Filament 3.3 dengan **custom theme dan layout** yang modern dan user-friendly.

### Akses Panel Admin
URL: `/admin`

### Fitur Custom Layout

#### 1. Custom Theme
- **Gradient Sidebar**: Background gradient dengan hover effects
- **Modern Cards**: Rounded corners dengan shadow effects
- **Custom Buttons**: Lift effect on hover
- **Custom Forms**: Focus ring dan smooth transitions
- **Dark Mode**: Full dark mode support
- **Custom Scrollbar**: Slim dan modern
- **Responsive**: Mobile-first design

#### 2. Custom Dashboard
- **Welcome Section**: Greeting dengan nama user dan waktu real-time
- **Stats Cards**: 4 cards untuk Penjualan, Pesanan, Produk, Pelanggan
- **Kasir Aktif**: List kasir yang sedang buka dengan total penjualan
- **Transaksi Terbaru**: 5 transaksi terakhir dengan status badge
- **Produk Stok Rendah**: Alert untuk produk dengan stok < 10
- **Pesanan Pending**: List pesanan yang menunggu proses

#### 3. Custom Login Page
- **Gradient Background**: Purple to blue gradient
- **Centered Layout**: Modern dan clean
- **Custom Logo**: Brand logo dengan icon
- **Shadow Effects**: Depth dan dimension

#### 4. Custom Widgets
- **Sales Chart**: Grafik penjualan 7 hari terakhir
- **Stats Overview**: Overview statistik real-time

#### 5. Panel Configuration
- **Brand**: Custom brand name dan logo
- **Colors**: Blue primary dengan color scheme lengkap
- **Font**: Inter font family
- **Sidebar**: Collapsible dengan width 16rem
- **Navigation Groups**: Organized navigation (Manajemen Toko, POS, Laporan, Pengaturan)
- **Notifications**: Database notifications dengan 30s polling
- **SPA Mode**: Single Page Application untuk performa optimal

### Build Custom Theme

```bash
# Development mode
npm run dev

# Production build
npm run build

# Clear cache
php artisan optimize:clear
php artisan filament:cache-components
```

### Dokumentasi Custom Layout

Lihat dokumentasi lengkap di:
- `docs/FILAMENT_CUSTOMIZATION.md` - Dokumentasi teknis lengkap
- `docs/CUSTOM_LAYOUT_GUIDE.md` - Panduan penggunaan

### Sumber Daya yang Tersedia

#### 1. Manajemen Produk
- **Tampilan Daftar**: Tabel produk dengan pencarian, pengurutan, dan filter
- **Buat/Edit**: Formulir lengkap dengan unggah gambar
- **Bidang**:
  - Toko (Dropdown)
  - Nama Produk
  - Deskripsi
  - Harga
  - Harga per Satuan
  - Satuan (bawaan: pcs)
  - Stok
  - Gambar (Unggah Berkas)

#### 2. Manajemen Pesanan
- **Tampilan Daftar**: Daftar pesanan dengan status
- **Tampilan Detail**: Informasi lengkap pesanan
- **Tindakan**: Perbarui status, batalkan pesanan

#### 3. Manajemen Pelanggan
- **Operasi CRUD**: Buat, Baca, Perbarui, Hapus
- **Relasi**: Lihat pesanan dan ulasan pelanggan

#### 4. Manajemen Toko
- **Informasi Toko**: Nama, deskripsi, alamat, kontak
- **Produk**: Daftar produk per toko

#### 5. Manajemen Ulasan
- **Moderasi**: Setujui/tolak ulasan
- **Analisis Penilaian**: Statistik penilaian produk

### Fitur Dasbor
- **Statistik**: Total pesanan, pendapatan, pelanggan
- **Grafik**: Tren penjualan, produk populer
- **Aktivitas Terbaru**: Pesanan terbaru, ulasan

## Fitur Utama

### 1. Manajemen Produk
- **Operasi CRUD**: Buat, Baca, Perbarui, Hapus produk
- **Unggah Gambar**: Unggah dan manajemen gambar produk
- **Manajemen Stok**: Pelacakan stok otomatis
- **Kategori**: Sistem kategorisasi produk
- **Multi-Toko**: Satu produk per toko

### 2. Sistem Pesanan
- **Pembuatan Pesanan**: Pembuatan pesanan dengan validasi stok
- **Metode Pembayaran**: Transfer, COD, E-wallet, Kartu Kredit
- **Status Pesanan**: menunggu → diproses → selesai/dibatalkan
- **Pembatalan Pesanan**: Pembatalan dengan alasan
- **Pengurangan Stok**: Otomatis mengurangi stok saat pesanan

### 3. Sistem Pengiriman
- **Pelacakan Pengiriman**: Nomor resi otomatis
- **Biaya Pengiriman**: Kalkulasi ongkos kirim
- **Manajemen Alamat**: Alamat pengiriman
- **Pelacakan Status**: menunggu → dikirim → diterima

### 4. Ulasan dan Penilaian
- **Ulasan Produk**: Pelanggan dapat memberikan ulasan
- **Sistem Penilaian**: Penilaian 1-5 bintang
- **Pembelian Terverifikasi**: Penandaan untuk pembelian terverifikasi
- **Moderasi Ulasan**: Admin dapat moderasi ulasan

### 5. Pencarian dan Filter
- **Pencarian Global**: Pencarian produk dan toko
- **Filter Kategori**: Filter berdasarkan kategori
- **Rentang Harga**: Filter berdasarkan harga
- **Filter Toko**: Filter berdasarkan toko

### 6. Integrasi API
- **API RESTful**: Titik akhir lengkap untuk aplikasi mobile
- **Respons JSON**: Format respons yang konsisten
- **Penanganan Kesalahan**: Penanganan kesalahan yang tepat
- **Paginasi**: Paginasi untuk data daftar

## Konfigurasi

### Variabel Lingkungan (.env)

#### Pengaturan Aplikasi
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:kunci_yang_dibuat
APP_DEBUG=true
APP_URL=http://localhost
```

#### Konfigurasi Basis Data
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ecommerce
DB_USERNAME=root
DB_PASSWORD=123456
```

#### Sesi & Cache
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
```

#### Konfigurasi Surel
```env
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Penyimpanan Berkas
- **Penyimpanan Lokal**: `storage/app/public`
- **Gambar Produk**: `storage/app/public/products`
- **Akses Publik**: Melalui rute `/storage`

### Konfigurasi Antrian
- **Driver**: Basis Data
- **Tabel Pekerjaan**: `jobs`
- **Pekerjaan Gagal**: `failed_jobs`

## Pengujian

### Menjalankan Pengujian
```bash
# Jalankan semua pengujian
composer test

# Jalankan pengujian spesifik
php artisan test --filter=ProductTest

# Jalankan dengan cakupan
php artisan test --coverage
```

### Struktur Pengujian
```
tests/
├── Feature/          # Pengujian integrasi
│   ├── ProductTest.php
│   ├── OrderTest.php
│   └── CustomerTest.php
└── Unit/             # Pengujian unit
    ├── Models/
    └── Services/
```

### Pengujian API
Gunakan alat seperti Postman atau Insomnia untuk menguji titik akhir API.

## Deployment

### 🚀 Deploy ke Railway.app (Recommended)

Deploy aplikasi ini ke cloud dalam 5 menit dengan Railway.app!

**Quick Start:**

1. Push ke GitHub:
   ```bash
   git add .
   git commit -m "Ready for deployment"
   git push origin main
   ```

2. Deploy ke Railway:
   - Login ke [railway.app](https://railway.app)
   - New Project > Deploy from GitHub
   - Pilih repository ini
   - Add MySQL database
   - Generate APP_KEY
   - Done!

**Dokumentasi Lengkap:**
- 📖 [Railway Quick Start](RAILWAY_QUICKSTART.md)
- 📚 [Railway Deployment Guide](docs/RAILWAY_DEPLOYMENT.md)
- ✅ [Deployment Checklist](DEPLOYMENT_CHECKLIST.md)

**Automated Setup:**
```bash
# Install Railway CLI
npm install -g @railway/cli

# Run setup script
bash scripts/railway-setup.sh
```

**Free Tier:**
- $5 credit per bulan
- Cukup untuk demo dan testing
- Auto-deploy dari GitHub
- SSL gratis

---

### Production Setup (VPS/Dedicated Server)

1. **Server Requirements**
   - PHP 8.2+
   - MySQL 8.0+
   - Nginx/Apache
   - SSL Certificate

2. **Environment Configuration**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_DATABASE=your_db_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password

# Cache & Session
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_email_password
```

3. **Deployment Commands**
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Build assets
npm run build

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Set permissions
chmod -R 755 storage bootstrap/cache
```

4. **Web Server Configuration**

**Nginx Configuration:**
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

### Monitoring & Maintenance

1. **Log Monitoring**
```bash
# View logs
php artisan pail

# Clear logs
php artisan log:clear
```

2. **Queue Monitoring**
```bash
# Start queue worker
php artisan queue:work --daemon

# Monitor failed jobs
php artisan queue:failed
```

3. **Database Backup**
```bash
# Create backup
mysqldump -u username -p database_name > backup.sql

# Restore backup
mysql -u username -p database_name < backup.sql
```

## Troubleshooting

### Common Issues

1. **Storage Link Error**
```bash
php artisan storage:link
```

2. **Permission Issues**
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

3. **Database Connection Error**
- Check database credentials in `.env`
- Ensure MySQL service is running
- Verify database exists

4. **Composer Memory Limit**
```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

## Kontribusi

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## Lisensi

Proyek ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lengkap.

## Support

Untuk pertanyaan atau dukungan, silakan hubungi:
- Email: admin@example.com
- GitHub Issues: [Create Issue](https://github.com/fatahgilang/ecommerce/issues)

---

**Dibuat dengan ❤️ menggunakan Laravel & Filament**

### Titik Akhir Kasir/POS

#### GET /cash-registers/current?user_id={id}
Mendapatkan kasir aktif untuk user
```json
Respons:
{
  "cash_register": {
    "id": 1,
    "register_name": "Kasir Utama",
    "opening_balance": "500000.00",
    "total_sales": "240000.00",
    "total_cash": "165000.00",
    "total_card": "75000.00",
    "status": "open",
    "opened_at": "02 Feb 2025 08:00",
    "cashier": "Kasir 1"
  }
}
```

#### POST /cash-registers/open
Membuka kasir baru
```json
Permintaan:
{
  "register_name": "Kasir Utama",
  "opening_balance": 500000,
  "user_id": 1
}
```

#### POST /cash-registers/{id}/close
Menutup kasir
```json
Permintaan:
{
  "closing_balance": 650000,
  "notes": "Tutup shift sore"
}
```

#### GET /cash-registers/history
Riwayat kasir dengan filter
```json
Parameter:
- user_id (optional)
- date_from (optional)
- date_to (optional)
- per_page (optional, default: 10)
```

#### GET /transactions
Daftar transaksi kasir
```json
Parameter:
- cash_register_id (optional)
- status (optional)
- date_from (optional)
- date_to (optional)

Respons:
{
  "data": [
    {
      "id": 1,
      "transaction_number": "TRX-20250202-0001",
      "customer": {
        "id": 1,
        "name": "Walk-in Customer"
      },
      "cashier": "Kasir 1",
      "total_amount": "165000.00",
      "formatted_total": "Rp 165.000",
      "payment_method": "cash",
      "status": "completed",
      "items_count": 2,
      "created_at": "02 Feb 2025 10:30"
    }
  ]
}
```

#### POST /transactions
Buat transaksi baru
```json
Permintaan:
{
  "cash_register_id": 1,
  "customer_id": null,
  "user_id": 1,
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "discount_amount": 0
    }
  ],
  "payment_method": "cash",
  "paid_amount": 200000,
  "discount_code": "TODAY10",
  "tax_rate": 10,
  "notes": "Transaksi walk-in"
}

Respons:
{
  "message": "Transaction created successfully",
  "transaction": {
    "id": 1,
    "transaction_number": "TRX-20250202-0001",
    "total_amount": "165000.00",
    "formatted_total": "Rp 165.000",
    "change_amount": "35000.00",
    "status": "completed"
  }
}
```

#### GET /transactions/{id}
Detail transaksi lengkap
```json
Respons:
{
  "id": 1,
  "transaction_number": "TRX-20250202-0001",
  "customer": {
    "id": 1,
    "name": "Walk-in Customer",
    "phone": "08123456789"
  },
  "cashier": {
    "id": 1,
    "name": "Kasir 1"
  },
  "items": [
    {
      "id": 1,
      "product": {
        "id": 1,
        "name": "Produk A",
        "image": "http://localhost:8000/storage/products/image.jpg"
      },
      "quantity": 2,
      "unit_price": "75000.00",
      "discount_amount": "0.00",
      "total_price": "150000.00"
    }
  ],
  "subtotal": "150000.00",
  "tax_amount": "15000.00",
  "discount_amount": "0.00",
  "total_amount": "165000.00",
  "paid_amount": "200000.00",
  "change_amount": "35000.00",
  "payment_method": "cash",
  "status": "completed",
  "can_be_refunded": true,
  "created_at": "02 Feb 2025 10:30"
}
```

#### POST /transactions/{id}/cancel
Batalkan transaksi
```json
Respons:
{
  "message": "Transaction cancelled successfully",
  "transaction": {
    "id": 1,
    "transaction_number": "TRX-20250202-0001",
    "status": "cancelled"
  }
}
```

#### POST /transactions/{id}/refund
Refund transaksi
```json
Permintaan:
{
  "reason": "Barang rusak, customer minta refund"
}
```

#### GET /transactions/{id}/receipt
Data struk transaksi
```json
Respons:
{
  "receipt_data": {
    "transaction_number": "TRX-20250202-0001",
    "date": "02/02/2025 10:30",
    "cashier": "Kasir 1",
    "customer": "Walk-in Customer",
    "items": [
      {
        "name": "Produk A",
        "quantity": 2,
        "unit_price": "75000.00",
        "total": "150000.00"
      }
    ],
    "subtotal": "150000.00",
    "tax_amount": "15000.00",
    "discount_amount": "0.00",
    "total_amount": "165000.00",
    "paid_amount": "200000.00",
    "change_amount": "35000.00",
    "payment_method": "cash"
  }
}
```

### Titik Akhir Diskon

#### GET /discounts
Daftar diskon aktif
```json
Parameter:
- code (optional) - Filter berdasarkan kode

Respons:
[
  {
    "id": 1,
    "name": "Diskon Hari Ini",
    "code": "TODAY10",
    "type": "percentage",
    "value": "10.00",
    "minimum_amount": "50000.00",
    "maximum_discount": "25000.00",
    "start_date": "2025-02-02",
    "end_date": "2025-02-09",
    "is_active": true
  }
]
```

#### POST /discounts/validate
Validasi kode diskon
```json
Permintaan:
{
  "code": "TODAY10",
  "amount": 100000
}

Respons:
{
  "valid": true,
  "discount": {
    "id": 1,
    "name": "Diskon Hari Ini",
    "type": "percentage",
    "value": "10.00",
    "discount_amount": "10000.00"
  }
}
```

### Titik Akhir Laporan

#### GET /reports/sales
Laporan penjualan
```json
Parameter:
- date_from (optional, default: awal bulan)
- date_to (optional, default: hari ini)
- cash_register_id (optional)

Respons:
{
  "period": {
    "from": "2025-02-01",
    "to": "2025-02-02"
  },
  "summary": {
    "total_transactions": 15,
    "total_sales": "2450000.00",
    "average_transaction": "163333.33",
    "total_tax": "245000.00",
    "total_discount": "125000.00"
  },
  "daily_sales": [
    {
      "date": "2025-02-01",
      "transaction_count": 8,
      "total_sales": "1200000.00",
      "average_transaction": "150000.00"
    }
  ],
  "payment_methods": [
    {
      "payment_method": "cash",
      "total": "1470000.00"
    },
    {
      "payment_method": "card",
      "total": "980000.00"
    }
  ]
}
```

#### GET /reports/products
Laporan produk terlaris
```json
Parameter:
- date_from (optional)
- date_to (optional)
- limit (optional, default: 20)

Respons:
{
  "period": {
    "from": "2025-02-01",
    "to": "2025-02-02"
  },
  "best_selling_products": [
    {
      "id": 1,
      "product_name": "Produk A",
      "product_price": "75000.00",
      "stock": 45,
      "total_sold": 25,
      "total_revenue": "1875000.00",
      "transaction_count": 15
    }
  ],
  "low_stock_products": [
    {
      "id": 5,
      "product_name": "Produk E",
      "stock": 3,
      "product_price": "50000.00"
    }
  ]
}
```

#### GET /reports/customers
Laporan pelanggan
```json
Parameter:
- date_from (optional)
- date_to (optional)
- limit (optional, default: 20)

Respons:
{
  "period": {
    "from": "2025-02-01",
    "to": "2025-02-02"
  },
  "top_customers": [
    {
      "id": 1,
      "name": "Customer A",
      "email": "customer@example.com",
      "phone": "08123456789",
      "transaction_count": 5,
      "total_spent": "750000.00",
      "average_transaction": "150000.00"
    }
  ],
  "new_customers_count": 3
}
```

#### GET /reports/cashiers
Laporan performa kasir
```json
Parameter:
- date_from (optional)
- date_to (optional)

Respons:
{
  "period": {
    "from": "2025-02-01",
    "to": "2025-02-02"
  },
  "cashier_performance": [
    {
      "id": 1,
      "name": "Kasir 1",
      "transaction_count": 12,
      "total_sales": "1800000.00",
      "average_transaction": "150000.00",
      "first_transaction": "2025-02-01 08:15:00",
      "last_transaction": "2025-02-02 17:45:00"
    }
  ],
  "register_sessions": [
    {
      "id": 1,
      "register_name": "Kasir Utama",
      "cashier": "Kasir 1",
      "opening_balance": "500000.00",
      "closing_balance": "650000.00",
      "total_sales": "240000.00",
      "status": "closed",
      "opened_at": "02 Feb 2025 08:00",
      "closed_at": "02 Feb 2025 17:00",
      "duration": "9 hours"
    }
  ]
}
```

#### GET /reports/dashboard
Dashboard summary untuk hari ini
```json
Respons:
{
  "today": {
    "sales": "450000.00",
    "transactions": 8,
    "customers": 5
  },
  "yesterday": {
    "sales": "380000.00",
    "transactions": 6
  },
  "this_month": {
    "sales": "2450000.00",
    "transactions": 45
  },
  "last_month": {
    "sales": "2100000.00",
    "transactions": 38
  },
  "comparisons": {
    "sales_vs_yesterday": 18.42,
    "transactions_vs_yesterday": 33.33,
    "sales_vs_last_month": 16.67
  },
  "active_cash_registers": 2,
  "low_stock_products": 3,
  "recent_transactions": [
    {
      "id": 15,
      "transaction_number": "TRX-20250202-0015",
      "customer": "Walk-in",
      "cashier": "Kasir 1",
      "total_amount": "125000.00",
      "formatted_total": "Rp 125.000",
      "created_at": "17:45"
    }
  ]
}
```

## Konfigurasi Bahasa Indonesia

Sistem ini telah dikonfigurasi untuk menggunakan bahasa Indonesia sebagai bahasa default:

### Konfigurasi Aplikasi
```env
APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID
```

### Fitur Bahasa Indonesia
- ✅ **Interface Admin Panel**: Semua menu, form, dan tabel dalam bahasa Indonesia
- ✅ **Pesan Validasi**: Pesan error dan validasi dalam bahasa Indonesia
- ✅ **API Response**: Semua response API menggunakan bahasa Indonesia
- ✅ **Navigation Groups**: 
  - **Manajemen Toko**: Pelanggan, Pesanan, Produk
  - **Manajemen POS**: Kasir, Transaksi, Diskon
- ✅ **Form Labels**: Semua label form dan field dalam bahasa Indonesia
- ✅ **Table Headers**: Header tabel dan kolom dalam bahasa Indonesia
- ✅ **Button Actions**: Tombol aksi seperti "Buat", "Edit", "Hapus", "Lihat"
- ✅ **Filter Labels**: Label filter dan opsi dalam bahasa Indonesia
- ✅ **Status Badges**: Status seperti "Aktif", "Buka", "Tutup", "Selesai", dll

### Menu Admin Panel (Bahasa Indonesia)

#### Manajemen Toko
- **Pelanggan**: Manajemen data pelanggan
- **Pesanan**: Manajemen pesanan online
- **Produk**: Manajemen produk dan inventori

#### Manajemen POS
- **Kasir**: Manajemen cash register dan shift kasir
- **Transaksi**: Monitoring transaksi penjualan
- **Diskon**: Manajemen diskon dan promo

### Contoh API Response dalam Bahasa Indonesia

#### Kasir Tidak Aktif
```json
{
  "message": "Tidak ada kasir aktif ditemukan",
  "cash_register": null
}
```

#### Transaksi Berhasil
```json
{
  "message": "Transaksi berhasil dibuat",
  "transaction": {
    "id": 1,
    "transaction_number": "TRX-20250202-0001",
    "total_amount": "165000.00",
    "status": "completed"
  }
}
```

#### Error Validasi
```json
{
  "message": "Validasi gagal",
  "errors": {
    "name": ["Kolom nama wajib diisi."],
    "email": ["Kolom email harus berupa alamat email yang valid."]
  }
}
```

### File Bahasa yang Tersedia
- `lang/id/validation.php` - Pesan validasi Laravel
- `lang/id/filament.php` - Interface Filament Admin Panel

### Customisasi Bahasa
Untuk mengubah teks tertentu, edit file bahasa yang sesuai:
```bash
# Pesan validasi
nano lang/id/validation.php

# Interface admin panel
nano lang/id/filament.php
```