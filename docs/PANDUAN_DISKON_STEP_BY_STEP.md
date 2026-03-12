# Panduan Step-by-Step: Cara Menerapkan Diskon pada Produk

## 🎯 Langkah-langkah Lengkap

### 1. Akses Admin Panel
1. Buka browser dan ketik URL: `http://localhost/admin` (atau domain Anda)
2. Login dengan kredensial admin:
   - **Email**: `admin@tokomakmur.com`
   - **Password**: `password123`

### 2. Navigasi ke Menu Produk
1. Setelah login, lihat sidebar kiri
2. Klik menu **"Produk"** di bawah grup **"Manajemen Toko"**
3. Anda akan melihat daftar semua produk

### 3. Pilih Produk untuk Diberi Diskon
1. Dari daftar produk, pilih produk yang ingin diberi diskon
2. Klik tombol **"Edit"** (ikon pensil) di kolom Actions
3. Form edit produk akan terbuka

### 4. Aktifkan Fitur Diskon
1. Scroll ke bawah hingga menemukan section **"Pengaturan Diskon"**
2. Klik section tersebut untuk membukanya (jika masih collapsed)
3. Aktifkan toggle **"Aktifkan Diskon"** dengan mengkliknya
4. Setelah diaktifkan, field-field diskon akan muncul

### 5. Pilih Jenis Diskon

#### Opsi A: Diskon Persentase
**Contoh: Diskon 20% untuk Flash Sale**
1. Kosongkan field **"Harga Diskon"** (biarkan kosong)
2. Isi field **"Persentase Diskon (%)"** dengan angka: `20`
3. Sistem akan otomatis menghitung harga diskon

#### Opsi B: Diskon Harga Tetap
**Contoh: Harga khusus Rp 50.000**
1. Isi field **"Harga Diskon"** dengan: `50000`
2. Kosongkan field **"Persentase Diskon (%)"** (biarkan kosong)
3. Harga akan langsung menjadi Rp 50.000

### 6. Atur Periode Diskon
1. **Tanggal Mulai Diskon**: 
   - Klik field dan pilih tanggal mulai
   - Default: hari ini
   - Contoh: `2026-03-05` (hari ini)

2. **Tanggal Berakhir Diskon**:
   - Klik field dan pilih tanggal berakhir
   - Default: 30 hari dari sekarang
   - Contoh: `2026-03-12` (seminggu dari sekarang)

### 7. Simpan Perubahan
1. Scroll ke bawah form
2. Klik tombol **"Simpan"** atau **"Update"**
3. Akan muncul notifikasi sukses
4. Diskon langsung aktif sesuai periode yang ditentukan

## 📋 Contoh Praktis

### Contoh 1: Flash Sale Smartphone
```
Produk: iPhone 14 Pro
Harga Normal: Rp 15.000.000
Target: Diskon 25% untuk 24 jam

Langkah:
1. Edit produk "iPhone 14 Pro"
2. Aktifkan diskon
3. Isi "Persentase Diskon": 25
4. Tanggal mulai: hari ini
5. Tanggal berakhir: besok
6. Simpan

Hasil:
- Harga tampil: ~~Rp 15.000.000~~ Rp 11.250.000
- Badge: -25%
- Hemat: Rp 3.750.000
```

### Contoh 2: Promo Beras Mingguan
```
Produk: Beras Premium 5kg
Harga Normal: Rp 75.000
Target: Harga khusus Rp 65.000 selama seminggu

Langkah:
1. Edit produk "Beras Premium 5kg"
2. Aktifkan diskon
3. Isi "Harga Diskon": 65000
4. Tanggal mulai: hari ini
5. Tanggal berakhir: 7 hari ke depan
6. Simpan

Hasil:
- Harga tampil: ~~Rp 75.000~~ Rp 65.000
- Badge: -13% (otomatis dihitung)
- Hemat: Rp 10.000
```

## ✅ Verifikasi Diskon Berhasil

### 1. Cek di Tabel Produk (Admin)
Setelah menyimpan, kembali ke daftar produk dan perhatikan:
- **Kolom "Diskon"**: Ikon centang hijau ✅
- **Kolom "Harga Saat Ini"**: Menampilkan harga setelah diskon (badge hijau)
- **Kolom "Diskon (%)"**: Menampilkan persentase diskon (badge kuning)

### 2. Cek di Website (Frontend)
1. Buka website utama (bukan admin)
2. Cari produk yang diberi diskon
3. Produk akan menampilkan:
   - **Badge merah** dengan persentase diskon (contoh: -25%)
   - **Harga asli dicoret** (contoh: ~~Rp 100.000~~)
   - **Harga diskon** dalam warna biru (contoh: Rp 75.000)
   - **Info penghematan** (contoh: Hemat Rp 25.000)

### 3. Test Keranjang Belanja
1. Tambahkan produk diskon ke keranjang
2. Buka halaman keranjang
3. Pastikan harga yang masuk adalah harga setelah diskon
4. Info diskon tetap ditampilkan di keranjang

## ⚠️ Tips Penting

### 1. Validasi Otomatis
- **Harga diskon** harus lebih kecil dari harga asli
- **Persentase** harus antara 0-100%
- **Tanggal berakhir** harus setelah tanggal mulai
- **Tidak boleh** isi harga diskon DAN persentase bersamaan

### 2. Prioritas Diskon
Jika tidak sengaja mengisi keduanya:
- **Harga diskon tetap** akan diprioritaskan
- **Persentase diskon** akan diabaikan

### 3. Status Aktif/Nonaktif
Diskon otomatis:
- **Aktif** jika dalam periode yang ditentukan
- **Nonaktif** jika di luar periode
- **Nonaktif** jika toggle "Aktifkan Diskon" dimatikan

## 🔧 Troubleshooting

### Masalah: Diskon Tidak Muncul di Website
**Solusi:**
1. Pastikan toggle "Aktifkan Diskon" sudah ON
2. Cek tanggal mulai dan berakhir
3. Pastikan ada nilai di "Harga Diskon" ATAU "Persentase Diskon"
4. Clear cache:
   ```bash
   php artisan optimize:clear
   npm run build
   ```

### Masalah: Error Saat Menyimpan
**Kemungkinan Penyebab:**
- Harga diskon lebih besar dari harga asli
- Persentase di luar range 0-100
- Tanggal berakhir sebelum tanggal mulai
- Mengisi harga diskon dan persentase bersamaan

### Masalah: Harga Tidak Berubah di Frontend
**Solusi:**
1. Refresh halaman website
2. Clear browser cache (Ctrl+F5)
3. Cek apakah diskon masih dalam periode aktif

## 📊 Monitoring Diskon

### Cek Produk dengan Diskon Aktif
1. Buka menu **"Produk"** di admin
2. Lihat kolom **"Diskon"** - yang ada ikon centang hijau berarti aktif
3. Kolom **"Harga Saat Ini"** menampilkan harga setelah diskon

### Laporan Penjualan Produk Diskon
1. Buka menu **"Pesanan"**
2. Filter berdasarkan tanggal
3. Lihat produk mana yang paling banyak terjual saat diskon

## 🎯 Best Practices

### 1. Perencanaan Diskon
- **Flash Sale**: 30-50% untuk 1-24 jam
- **Promo Mingguan**: 10-25% untuk 1 minggu  
- **Clearance**: 40-70% untuk produk lama

### 2. Timing yang Tepat
- **Weekend**: Untuk produk lifestyle
- **Hari kerja**: Untuk produk kebutuhan sehari-hari
- **Menjelang event**: Lebaran, Natal, dll

### 3. Komunikasi
- Update banner website
- Informasikan ke customer via WhatsApp/sosmed
- Buat urgency dengan countdown timer

Dengan mengikuti panduan ini, Anda dapat dengan mudah menerapkan diskon pada produk tertentu dan memaksimalkan penjualan toko Anda! 🚀