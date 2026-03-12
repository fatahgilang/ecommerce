# 🖨️ Panduan Lengkap Thermal Printer - Toko Makmur

## Fitur Utama

### ✨ Keunggulan
- **Silent Printing**: Cetak struk tanpa dialog print browser (Ctrl+P)
- **Auto Print**: Otomatis menawarkan cetak struk setelah checkout
- **Thermal Format**: Format struk optimal untuk printer thermal 58mm
- **Fallback Support**: Jika QZ Tray tidak tersedia, fallback ke browser print
- **Multi Printer**: Mendukung berbagai merk printer thermal

### 🎯 Cara Kerja
1. Customer checkout di frontend
2. Pesanan berhasil dibuat
3. Muncul konfirmasi "Cetak struk sekarang?"
4. Jika Ya: Struk langsung keluar dari printer thermal
5. Jika Tidak: Bisa cetak ulang kapan saja

## 📦 Instalasi QZ Tray

### Windows
1. Download QZ Tray dari https://qz.io/download/
2. Jalankan installer `qz-tray-x.x.x.exe`
3. Ikuti wizard instalasi
4. QZ Tray akan otomatis berjalan di system tray

### macOS
1. Download QZ Tray dari https://qz.io/download/
2. Buka file `.pkg` yang didownload
3. Ikuti wizard instalasi
4. Buka QZ Tray dari Applications

### Linux
```bash
# Download
wget https://github.com/qzind/tray/releases/download/v2.2.2/qz-tray_2.2.2_amd64.deb

# Install
sudo dpkg -i qz-tray_2.2.2_amd64.deb

# Run
qz-tray
```

## 🔧 Konfigurasi Printer

### 1. Hubungkan Printer
- **USB**: Colokkan kabel USB printer ke komputer
- **Bluetooth**: Pair printer Bluetooth dengan komputer

### 2. Install Driver Printer
- Download driver dari website manufacturer
- Install driver sesuai OS
- Test print dari aplikasi lain untuk memastikan printer berfungsi

### 3. Setup di Aplikasi
1. Buka aplikasi Toko Makmur
2. Klik icon **"Printer"** di navigation bar
3. Klik **"Refresh Printer"**
4. Pilih printer thermal dari dropdown
5. Klik **"Test Print"** untuk test

## 📝 Format Struk

### Contoh Output (58mm)
```
================================
      TOKO MAKMUR JAYA
    Jl. Contoh No. 123
   Telp: 0812-3456-7890
================================

No. Pesanan          #12345
Tanggal    2026-03-05 14:30
Kasir                Frontend
Pembayaran              CASH
--------------------------------

Produk Test
1 x Rp10,000        Rp10,000

Beras Premium 5kg
2 x Rp75,000       Rp150,000

--------------------------------
Subtotal           Rp160,000
Diskon (HARI10)    -Rp16,000
================================
TOTAL              Rp144,000
================================

     PEMBAYARAN TUNAI

      Terima Kasih
   Atas Kunjungan Anda



```

### Informasi yang Dicetak
- Header toko (nama, alamat, telepon)
- Nomor pesanan
- Tanggal dan waktu
- Nama kasir
- Metode pembayaran
- Daftar produk (nama, qty, harga, total)
- Subtotal
- Diskon (jika ada)
- Total pembayaran
- Footer ucapan terima kasih

## 🖨️ Printer yang Didukung

### Tested & Compatible
- ✅ Epson TM-T82
- ✅ Epson TM-T88V
- ✅ Star TSP143III
- ✅ Bixolon SRP-350III
- ✅ Xprinter XP-58IIH
- ✅ Zjiang ZJ-5890K
- ✅ Generic ESC/POS thermal printer

### Spesifikasi Minimum
- Lebar kertas: 58mm atau 80mm
- Protocol: ESC/POS
- Interface: USB, Bluetooth, atau Serial
- Driver: Terinstall di OS

## 🔍 Troubleshooting

### QZ Tray Tidak Terdeteksi
**Masalah**: Error "QZ Tray tidak terdeteksi"

**Solusi**:
1. Pastikan QZ Tray sudah terinstall
2. Cek QZ Tray berjalan di system tray
3. Restart QZ Tray
4. Refresh browser (Ctrl+F5)

### Printer Tidak Muncul di List
**Masalah**: Dropdown printer kosong

**Solusi**:
1. Pastikan printer sudah terhubung (USB/Bluetooth)
2. Install driver printer
3. Test print dari aplikasi lain (Notepad, dll)
4. Klik "Refresh Printer" di aplikasi
5. Restart komputer jika perlu

### Struk Tidak Keluar
**Masalah**: Klik print tapi tidak ada output

**Solusi**:
1. Cek kertas printer (jangan sampai habis)
2. Cek printer online (tidak error/offline)
3. Test print dari "Test Print" button
4. Cek koneksi USB/Bluetooth
5. Restart printer

### Format Struk Berantakan
**Masalah**: Teks terpotong atau tidak rapi

**Solusi**:
1. Pastikan menggunakan printer 58mm
2. Cek setting printer (paper size)
3. Update driver printer
4. Gunakan fallback browser print

### Browser Print Muncul
**Masalah**: Dialog print browser muncul (bukan silent print)

**Solusi**:
1. Ini adalah fallback mode (normal)
2. Install QZ Tray untuk silent print
3. Pilih printer di pengaturan
4. Test print untuk verifikasi

## 💡 Tips & Best Practices

### Untuk Kasir
1. **Setup Sekali**: Pilih printer saat pertama kali, tersimpan otomatis
2. **Test Rutin**: Test print setiap pagi sebelum operasional
3. **Stok Kertas**: Selalu sediakan kertas thermal cadangan
4. **Bersihkan Printer**: Bersihkan head printer secara berkala

### Untuk Admin
1. **Backup Printer**: Sediakan printer cadangan
2. **Update Driver**: Update driver printer secara berkala
3. **Monitor Usage**: Pantau penggunaan kertas thermal
4. **Training**: Latih kasir cara troubleshoot basic

### Untuk IT Support
1. **Dokumentasi**: Catat merk dan model printer yang digunakan
2. **Driver Backup**: Simpan installer driver printer
3. **QZ Tray Update**: Update QZ Tray ke versi terbaru
4. **Network Print**: Pertimbangkan network printer untuk multi-kasir

## 🔐 Keamanan

### Certificate Signing
QZ Tray menggunakan certificate signing untuk keamanan:
- Aplikasi harus di-trust oleh QZ Tray
- Certificate valid untuk domain tertentu
- Tidak bisa digunakan untuk print arbitrary content

### Privacy
- QZ Tray hanya berjalan lokal
- Tidak mengirim data ke server eksternal
- Data struk hanya dicetak, tidak disimpan

## 📊 Monitoring

### Cek Status Printer
```javascript
// Di browser console
thermalPrinter.getPrinters().then(printers => {
    console.log('Available printers:', printers);
});
```

### Cek Koneksi QZ Tray
```javascript
// Di browser console
qz.websocket.isActive().then(active => {
    console.log('QZ Tray active:', active);
});
```

## 🆘 Support

### Kontak
- Email: support@tokomakmur.com
- Telp: 0812-3456-7890
- Website: https://tokomakmur.com

### Resources
- QZ Tray Docs: https://qz.io/docs/
- GitHub Issues: https://github.com/qzind/tray/issues
- Community Forum: https://qz.io/support/

## 📚 Referensi

### ESC/POS Commands
- `\x1B\x40` - Initialize printer
- `\x1B\x61\x01` - Center align
- `\x1B\x61\x00` - Left align
- `\x1B\x45\x01` - Bold on
- `\x1B\x45\x00` - Bold off
- `\x1D\x56\x00` - Cut paper (full)
- `\x1D\x56\x01` - Cut paper (partial)

### Paper Sizes
- 58mm: 32 characters per line
- 80mm: 48 characters per line

### Encoding
- Default: ASCII
- Support: UTF-8 (untuk karakter Indonesia)

---

**Last Updated**: 2026-03-05
**Version**: 1.0.0