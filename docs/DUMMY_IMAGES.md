# Dummy Images untuk Produk

## Overview
Semua produk di database sekarang memiliki gambar dummy yang dihasilkan secara otomatis berdasarkan nama produk menggunakan layanan [placehold.co](https://placehold.co/).

## Implementasi

### Color Schemes
Gambar dummy menggunakan skema warna yang berbeda berdasarkan kategori produk:

| Kategori | Warna Background | Warna Text | Hex Code |
|----------|------------------|------------|----------|
| **Food** (Makanan & Minuman) | Merah | Putih | #FF6B6B / #FFFFFF |
| **Electronics** (Elektronik) | Teal | Putih | #4ECDC4 / #FFFFFF |
| **Fashion** (Pakaian & Aksesoris) | Kuning | Biru Tua | #FFE66D / #2C3E50 |
| **Fresh** (Produk Segar) | Mint | Biru Tua | #95E1D3 / #2C3E50 |
| **Grocery** (Kebutuhan Sehari-hari) | Pink | Putih | #F38181 / #FFFFFF |
| **Default** | Biru | Putih | #3498DB / #FFFFFF |

### Deteksi Kategori
Kategori produk dideteksi otomatis berdasarkan kata kunci dalam nama produk:

- **Food**: nasi, mie, gorengan, kopi, teh, gudeg, kerupuk, permen, rokok
- **Electronics**: smartphone, laptop, earphone, power bank, speaker, smartwatch, kabel
- **Fashion**: kaos, kemeja, dress, celana, blouse, jaket, sepatu, tas
- **Fresh**: ayam, ikan, sayur, tomat, bawang, cabai, pisang, jeruk
- **Grocery**: beras, minyak, gula, telur, susu, roti, sabun, shampo, pasta, deterjen

## Contoh URL Gambar

```
https://placehold.co/600x600/FF6B6B/FFFFFF?text=Nasi+Gudeg
https://placehold.co/600x600/4ECDC4/FFFFFF?text=Smartphone+Android
https://placehold.co/600x600/FFE66D/2C3E50?text=Kaos+Polos+Premium
https://placehold.co/600x600/95E1D3/2C3E50?text=Ayam+Potong+Segar
https://placehold.co/600x600/F38181/FFFFFF?text=Beras+Premium+5kg
```

## Update Gambar

### Menggunakan Seeder
Jalankan seeder untuk membuat produk baru dengan gambar dummy:

```bash
php artisan db:seed --class=ProductSeeder
```

### Update Manual
Untuk mengupdate gambar produk yang sudah ada:

```bash
php artisan tinker
```

Kemudian jalankan script berikut:

```php
$products = App\Models\Product::all();
foreach ($products as $product) {
    // Logic untuk generate dummy image
    // (lihat ProductSeeder.php untuk implementasi lengkap)
}
```

## Mengganti dengan Gambar Real

Untuk mengganti gambar dummy dengan gambar asli:

1. Upload gambar ke `storage/app/public/products/`
2. Update field `image` di database dengan path relatif:
   ```php
   $product->image = 'products/nama-file.jpg';
   $product->save();
   ```

3. Atau gunakan Filament Admin Panel:
   - Buka halaman edit produk
   - Upload gambar baru melalui form
   - Gambar akan otomatis tersimpan dan path akan diupdate

## Kelebihan Pendekatan Ini

1. **Tidak perlu storage lokal** - Gambar di-host oleh layanan eksternal
2. **Otomatis** - Gambar dihasilkan berdasarkan nama produk
3. **Visual distinction** - Warna berbeda untuk kategori berbeda
4. **Mudah diidentifikasi** - Nama produk tertera di gambar
5. **Responsive** - Ukuran 600x600px cocok untuk berbagai device

## Catatan

- Gambar dummy ini hanya untuk development/testing
- Untuk production, sebaiknya gunakan gambar produk asli
- Layanan placehold.co gratis dan tidak memerlukan API key
- Jika ingin menggunakan layanan lain, edit method `generateDummyImage()` di `ProductSeeder.php`
