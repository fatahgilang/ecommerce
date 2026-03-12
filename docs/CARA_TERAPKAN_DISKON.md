# Cara Menerapkan Sistem Diskon dengan Minimum Pembelian

## Fitur Diskon yang Tersedia

Sistem diskon Toko Makmur mendukung:

### 1. Jenis Diskon
- **Persentase**: Diskon berdasarkan persentase dari total belanja
- **Jumlah Tetap**: Potongan harga dalam rupiah

### 2. Syarat dan Ketentuan
- **Minimum Pembelian**: Jumlah minimum yang harus dibeli untuk mendapat diskon
- **Maksimum Diskon**: Batas maksimal potongan (untuk diskon persentase)
- **Periode Berlaku**: Tanggal mulai dan berakhir diskon
- **Batas Penggunaan**: Jumlah maksimal penggunaan diskon
- **Status Aktif**: Diskon dapat diaktifkan/nonaktifkan

## Cara Mengelola Diskon (Admin)

### 1. Akses Menu Diskon
1. Login ke admin panel: `/admin`
2. Pilih menu **"Diskon"** di sidebar
3. Klik **"Buat Diskon"** untuk membuat diskon baru

### 2. Membuat Diskon Baru
**Form yang harus diisi:**

- **Nama Diskon**: Nama untuk identifikasi (contoh: "Diskon Hari Ini")
- **Kode Diskon**: Kode yang digunakan customer (contoh: "HARI10")
- **Jenis Diskon**: 
  - Pilih "Persentase" untuk diskon %
  - Pilih "Jumlah Tetap" untuk potongan rupiah
- **Nilai**: 
  - Untuk persentase: masukkan angka 1-100
  - Untuk jumlah tetap: masukkan nominal rupiah
- **Minimum Pembelian**: Jumlah minimum transaksi (Rp)
- **Maksimum Diskon**: Batas maksimal potongan (opsional, untuk persentase)
- **Tanggal Mulai**: Kapan diskon mulai berlaku
- **Tanggal Berakhir**: Kapan diskon berakhir
- **Batas Penggunaan**: Maksimal berapa kali bisa digunakan (kosongkan untuk unlimited)
- **Status Aktif**: Centang untuk mengaktifkan diskon

### 3. Contoh Pengaturan Diskon

**Diskon Persentase dengan Minimum Pembelian:**
```
Nama: Diskon Weekend 15%
Kode: WEEKEND15
Jenis: Persentase
Nilai: 15
Minimum Pembelian: 100,000
Maksimum Diskon: 50,000
Tanggal Mulai: 2026-03-08 (Sabtu)
Tanggal Berakhir: 2026-03-09 (Minggu)
Batas Penggunaan: 50
Status: Aktif
```

**Diskon Jumlah Tetap:**
```
Nama: Cashback 50 Ribu
Kode: CASHBACK50
Jenis: Jumlah Tetap
Nilai: 50000
Minimum Pembelian: 300,000
Tanggal Mulai: 2026-03-05
Tanggal Berakhir: 2026-03-31
Batas Penggunaan: 100
Status: Aktif
```

## Cara Menggunakan Diskon (Customer)

### 1. Di Halaman Keranjang
1. Tambahkan produk ke keranjang
2. Buka halaman **"Keranjang Belanja"**
3. Di bagian **"Ringkasan Belanja"**, temukan section **"Kode Diskon"**
4. Masukkan kode diskon di kolom input
5. Klik tombol **"Terapkan"**

### 2. Validasi Diskon
Sistem akan memvalidasi:
- ✅ Kode diskon valid dan aktif
- ✅ Tanggal masih berlaku
- ✅ Belum mencapai batas penggunaan
- ✅ Total belanja memenuhi minimum pembelian

### 3. Pesan Error yang Mungkin Muncul
- **"Kode diskon tidak ditemukan"**: Kode salah atau tidak ada
- **"Diskon sudah berakhir"**: Melewati tanggal berakhir
- **"Minimum pembelian Rp XXX"**: Total belanja kurang dari minimum
- **"Batas penggunaan sudah tercapai"**: Diskon sudah habis

### 4. Jika Diskon Berhasil
- Muncul notifikasi hijau dengan nama diskon
- Total belanja akan berkurang sesuai diskon
- Bisa menghapus diskon dengan klik "Hapus"

## Kode Diskon yang Tersedia (Contoh)

### Diskon Aktif Saat Ini:
1. **HARI10** - Diskon 10% (Min. Rp 50,000, Max. Rp 25,000)
2. **VIP20** - Diskon 20% (Min. Rp 200,000, Max. Rp 100,000)
3. **HEMAT20** - Potongan Rp 20,000 (Min. Rp 100,000)
4. **CASHBACK50** - Potongan Rp 50,000 (Min. Rp 300,000)
5. **NEWBIE15K** - Potongan Rp 15,000 (Min. Rp 75,000)

### Cara Test Diskon:
1. Tambahkan produk dengan total > Rp 50,000
2. Gunakan kode **HARI10**
3. Dapatkan diskon 10% (maksimal Rp 25,000)

## Monitoring Penggunaan Diskon

### 1. Laporan Diskon (Admin)
- Lihat kolom **"Digunakan"** di tabel diskon
- Monitor berapa kali setiap diskon dipakai
- Cek apakah mendekati batas penggunaan

### 2. Riwayat Pesanan dengan Diskon
- Menu **"Pesanan"** menampilkan kolom **"Kode Diskon"** dan **"Diskon"**
- Filter pesanan berdasarkan penggunaan diskon
- Analisis efektivitas setiap kode diskon

### 3. Laporan Penjualan
- Total diskon yang diberikan
- Dampak diskon terhadap penjualan
- ROI dari program diskon

## Tips Penggunaan Diskon

### Untuk Admin:
1. **Buat diskon bertahap**: Mulai dari minimum rendah, naikkan bertahap
2. **Batasi penggunaan**: Hindari kerugian dengan set batas penggunaan
3. **Monitor aktif**: Cek penggunaan diskon secara berkala
4. **Analisis efektivitas**: Lihat apakah diskon meningkatkan penjualan

### Untuk Customer:
1. **Kumpulkan belanja**: Tunggu sampai mencapai minimum pembelian
2. **Cek tanggal berlaku**: Pastikan diskon masih aktif
3. **Kombinasi produk**: Pilih produk yang tepat untuk mencapai minimum
4. **Simpan kode**: Catat kode diskon yang sering digunakan

## Troubleshooting

### Masalah Umum:
1. **Diskon tidak muncul**: Cek status aktif dan tanggal berlaku
2. **Minimum tidak tercapai**: Tambah produk atau ganti produk lebih mahal
3. **Kode tidak diterima**: Pastikan penulisan kode benar (case sensitive)
4. **Diskon hilang saat checkout**: Validasi ulang sebelum pembayaran

### Kontak Support:
Jika ada masalah dengan sistem diskon, hubungi admin toko atau tim IT.