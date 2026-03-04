# Penghapusan Database Pelanggan

## Ringkasan Perubahan

Database pelanggan (customers) telah dihapus dari sistem karena tidak diperlukan. Pesanan sekarang dibuat langsung tanpa data pelanggan.

## Perubahan yang Dilakukan

### 1. Database & Migrasi
- ✅ Dihapus tabel `customers`
- ✅ Dihapus kolom `customer_id` dari tabel `orders`
- ✅ Dihapus kolom `customer_id` dari tabel `reviews`
- ✅ Dihapus kolom `customer_id` dari tabel `transactions`
- ✅ Diperbarui migrasi asli untuk menghapus foreign key customer_id

### 2. Model
- ✅ Dihapus `app/Models/Customer.php`
- ✅ Diperbarui `app/Models/Order.php` - dihapus relasi customer
- ✅ Diperbarui `app/Models/Review.php` - dihapus relasi customer
- ✅ Diperbarui `app/Models/Transaction.php` - dihapus relasi customer

### 3. Controllers
- ✅ Dihapus `app/Http/Controllers/Api/CustomerController.php`
- ✅ Diperbarui `app/Http/Controllers/Frontend/CheckoutController.php` - tidak lagi membuat customer
- ✅ Diperbarui `app/Http/Controllers/Api/ReportController.php` - dihapus laporan customer

### 4. Filament Resources
- ✅ Dihapus `app/Filament/Admin/Resources/CustomerResource.php`
- ✅ Dihapus semua halaman CustomerResource (List, Create, Edit)
- ✅ Diperbarui `app/Filament/Admin/Resources/OrderResource.php` - dihapus kolom customer

### 5. Seeders
- ✅ Dihapus `database/seeders/CustomerSeeder.php`
- ✅ Diperbarui `database/seeders/OrderSeeder.php` - tidak lagi menggunakan customer
- ✅ Diperbarui `database/seeders/ReviewSeeder.php` - tidak lagi menggunakan customer
- ✅ Diperbarui `database/seeders/TransactionSeeder.php` - tidak lagi menggunakan customer
- ✅ Diperbarui `database/seeders/DatabaseSeeder.php` - dihapus CustomerSeeder

### 6. Routes
- ✅ Dihapus semua route `/api/v1/customers/*`
- ✅ Dihapus route `/api/v1/reports/customers`

## Struktur Tabel Orders (Setelah Perubahan)

```sql
CREATE TABLE orders (
    id BIGINT UNSIGNED PRIMARY KEY,
    product_id BIGINT UNSIGNED,
    product_quantity INT,
    total_price DECIMAL(10,2),
    payment_method VARCHAR(255),
    status ENUM('pending', 'processing', 'completed', 'cancelled'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

## Struktur Tabel Reviews (Setelah Perubahan)

```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED PRIMARY KEY,
    product_id BIGINT UNSIGNED,
    review TEXT,
    rating INT DEFAULT 5,
    is_verified_purchase BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
```

## Struktur Tabel Transactions (Setelah Perubahan)

```sql
CREATE TABLE transactions (
    id BIGINT UNSIGNED PRIMARY KEY,
    transaction_number VARCHAR(255) UNIQUE,
    cash_register_id BIGINT UNSIGNED,
    user_id BIGINT UNSIGNED,
    subtotal DECIMAL(15,2),
    tax_amount DECIMAL(15,2) DEFAULT 0,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    total_amount DECIMAL(15,2),
    payment_method ENUM('cash', 'card', 'ewallet', 'transfer', 'split'),
    paid_amount DECIMAL(15,2),
    change_amount DECIMAL(15,2) DEFAULT 0,
    status ENUM('pending', 'completed', 'cancelled', 'refunded') DEFAULT 'completed',
    notes TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (cash_register_id) REFERENCES cash_registers(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## Proses Checkout (Setelah Perubahan)

1. User menambahkan produk ke keranjang
2. User memilih metode pembayaran (BCA, Mandiri, BRI, GoPay, OVO, DANA)
3. Sistem membuat pesanan langsung tanpa data pelanggan
4. Pesanan tersimpan dengan informasi:
   - Produk yang dibeli
   - Jumlah produk
   - Total harga
   - Metode pembayaran
   - Status pesanan

## API Endpoints yang Dihapus

- `GET /api/v1/customers` - List customers
- `POST /api/v1/customers` - Create customer
- `GET /api/v1/customers/{id}` - Show customer
- `PUT /api/v1/customers/{id}` - Update customer
- `DELETE /api/v1/customers/{id}` - Delete customer
- `GET /api/v1/customers/{id}/orders` - Customer orders
- `GET /api/v1/customers/{id}/reviews` - Customer reviews
- `GET /api/v1/reports/customers` - Customer report

## Testing

Setelah perubahan, jalankan:

```bash
# Reset database dengan data baru
php artisan migrate:fresh --seed

# Clear cache
php artisan optimize:clear

# Build frontend assets
npm run build

# Test checkout dari browser
# 1. Buka http://localhost:8000
# 2. Tambahkan produk ke keranjang
# 3. Klik "Checkout"
# 4. Pilih metode pembayaran
# 5. Klik "Proses Pembayaran"
# 6. Cek di admin panel: http://localhost:8000/admin/orders
```

## Catatan Penting

- ✅ Pesanan sekarang dibuat tanpa data pelanggan
- ✅ Review produk tidak lagi terkait dengan pelanggan tertentu
- ✅ Transaksi POS tidak lagi menyimpan data pelanggan
- ✅ Admin panel tidak lagi menampilkan menu Pelanggan
- ✅ Checkout frontend berfungsi tanpa form data pelanggan

## Tanggal Perubahan

4 Maret 2026
