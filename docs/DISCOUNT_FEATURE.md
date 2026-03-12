# Fitur Diskon Produk

## Deskripsi
Sistem diskon khusus untuk produk tertentu yang memungkinkan admin memberikan diskon berdasarkan persentase atau harga tetap dengan periode waktu tertentu.

## Fitur Utama

### 1. Jenis Diskon
- **Diskon Persentase**: Diskon berdasarkan persentase (contoh: 15% off)
- **Diskon Harga Tetap**: Diskon dengan harga khusus (contoh: dari Rp 75.000 menjadi Rp 60.000)

### 2. Periode Diskon
- Tanggal mulai diskon
- Tanggal berakhir diskon
- Otomatis aktif/nonaktif berdasarkan periode

### 3. Tampilan Frontend
- Badge diskon pada kartu produk
- Harga asli dicoret
- Harga diskon ditampilkan
- Informasi penghematan
- Persentase diskon

## Cara Penggunaan

### Admin Panel (Filament)

1. **Menambah Diskon Produk**:
   - Buka menu "Produk" di admin panel
   - Edit produk yang ingin diberi diskon
   - Buka section "Pengaturan Diskon"
   - Aktifkan toggle "Aktifkan Diskon"
   - Pilih salah satu:
     - **Harga Diskon**: Masukkan harga khusus
     - **Persentase Diskon**: Masukkan persentase (0-100%)
   - Atur tanggal mulai dan berakhir
   - Simpan

2. **Melihat Status Diskon**:
   - Kolom "Diskon" menampilkan status aktif/nonaktif
   - Kolom "Harga Saat Ini" menampilkan harga dengan diskon
   - Kolom "Diskon (%)" menampilkan persentase diskon

### Frontend

1. **Tampilan Produk**:
   - Badge merah dengan persentase diskon
   - Harga asli dicoret
   - Harga diskon dalam warna biru
   - Informasi penghematan

2. **Keranjang Belanja**:
   - Harga yang ditampilkan adalah harga setelah diskon
   - Informasi harga asli dan penghematan (jika ada)

## Database Schema

### Tabel `products` (kolom tambahan):
- `discount_price`: Harga diskon tetap (nullable)
- `discount_percentage`: Persentase diskon (nullable)
- `discount_start_date`: Tanggal mulai diskon (nullable)
- `discount_end_date`: Tanggal berakhir diskon (nullable)
- `has_discount`: Status aktif diskon (boolean)

### Tabel `product_discounts` (pivot table):
- `id`: Primary key
- `product_id`: Foreign key ke products
- `discount_id`: Foreign key ke discounts
- `created_at`, `updated_at`: Timestamps

## Model Methods

### Product Model

```php
// Cek apakah produk memiliki diskon aktif
$product->hasActiveDiscount(): bool

// Dapatkan harga saat ini (dengan diskon jika ada)
$product->getCurrentPrice(): float

// Dapatkan jumlah diskon
$product->getDiscountAmount(): float

// Dapatkan persentase diskon
$product->getDiscountPercentage(): float

// Terapkan diskon ke produk
$product->applyDiscount($discountPrice, $discountPercentage, $startDate, $endDate)

// Hapus diskon dari produk
$product->removeDiscount()
```

## Contoh Data Seeder

```php
// Diskon persentase 15%
$product->applyDiscount(
    discountPercentage: 15,
    startDate: now()->toDateString(),
    endDate: now()->addDays(7)->toDateString()
);

// Diskon harga tetap
$product->applyDiscount(
    discountPrice: 60000,
    startDate: now()->toDateString(),
    endDate: now()->addDays(14)->toDateString()
);
```

## Testing

Untuk menguji fitur diskon:

1. **Admin Panel**:
   - Login sebagai admin
   - Buka menu Produk
   - Edit produk dan aktifkan diskon
   - Cek tampilan di tabel produk

2. **Frontend**:
   - Buka halaman produk
   - Cek tampilan badge diskon dan harga
   - Tambah ke keranjang
   - Cek harga di keranjang

3. **API**:
   - Endpoint `/api/products` akan menyertakan field diskon
   - Field yang tersedia: `has_discount`, `discount_price`, `discount_percentage`, dll.

## Catatan Penting

1. **Prioritas Diskon**: Jika ada diskon harga tetap dan persentase, harga tetap akan diprioritaskan
2. **Validasi Periode**: Diskon hanya aktif dalam periode yang ditentukan
3. **Checkout**: Sistem checkout menggunakan harga setelah diskon
4. **Stock**: Diskon tidak mempengaruhi perhitungan stok
5. **Cache**: Setelah mengubah diskon, jalankan `php artisan optimize:clear`

## Troubleshooting

### Diskon Tidak Muncul
- Pastikan `has_discount` = true
- Cek tanggal mulai dan berakhir
- Pastikan ada nilai di `discount_price` atau `discount_percentage`

### Harga Tidak Berubah
- Cek method `getCurrentPrice()` di model
- Pastikan frontend menggunakan harga dari `getCurrentPrice()`
- Build ulang assets: `npm run build`

### Error di Admin Panel
- Pastikan migrasi sudah dijalankan: `php artisan migrate`
- Cek import BelongsToMany di model Product
- Clear cache: `php artisan optimize:clear`