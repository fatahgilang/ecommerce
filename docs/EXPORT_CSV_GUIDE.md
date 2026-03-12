# 📥 Panduan Export Laporan ke CSV

## Overview

Fitur export CSV memungkinkan Anda mengunduh laporan dalam format CSV yang dapat dibuka di Excel, Google Sheets, atau aplikasi spreadsheet lainnya.

## 🎯 Cara Export

### Via Admin Panel

1. **Generate Laporan**
   ```
   - Login ke /admin
   - Klik menu "Laporan"
   - Pilih jenis laporan
   - Set tanggal periode
   - Klik "Generate Laporan"
   ```

2. **Export ke CSV**
   ```
   - Setelah laporan muncul
   - Klik tombol "Export CSV"
   - File otomatis ter-download
   ```

3. **Buka File**
   ```
   - Buka file CSV yang ter-download
   - Gunakan Excel, Google Sheets, atau LibreOffice
   - Data sudah terpisah per kolom
   ```

## 📊 Format File CSV

### Struktur File

```
=== HEADER ===
Laporan: LAPORAN PENJUALAN
Periode: 2026-03-01 s/d 2026-03-05
Tanggal Export: 2026-03-05 14:30:00

=== RINGKASAN ===
Total Pendapatan: 135,880,160
Total Transaksi: 50
Rata-rata Transaksi: 2,717,603
Rata-rata Harian: 22,646,693

=== DETAIL ===
Periode,Jumlah Transaksi,Pendapatan
2026-03-01,10,25000000
2026-03-02,12,30000000
...
```

### Kolom per Jenis Laporan

**Laporan Penjualan:**
- Periode
- Jumlah Transaksi
- Pendapatan

**Laporan Produk:**
- ID Produk
- Nama Produk
- Kategori
- Total Terjual
- Total Pendapatan
- Jumlah Order
- Stok Saat Ini
- Nilai Stok

**Laporan Kasir:**
- ID Kasir
- Nama Kasir
- Jumlah Transaksi
- Total Pendapatan
- Rata-rata Transaksi

**Laporan Kas:**
- Tanggal
- Jumlah Transaksi
- Pendapatan Tunai
- Pendapatan Non-Tunai
- Total Pendapatan

**Laporan Laba Rugi:**
- Kategori
- Jumlah

**Laporan Inventory:**
- ID Produk
- Nama Produk
- Kategori
- Stok
- Harga
- Nilai Stok
- Status

## 💡 Tips Penggunaan

### Di Excel

**1. Buka File CSV**
```
- Klik kanan file CSV
- Open With → Microsoft Excel
- Atau drag & drop ke Excel
```

**2. Format Angka**
```
- Pilih kolom angka
- Format → Number → Number
- Set decimal places: 0
- Use 1000 separator: Yes
```

**3. Format Mata Uang**
```
- Pilih kolom rupiah
- Format → Number → Currency
- Symbol: Rp
- Decimal places: 0
```

**4. Buat Grafik**
```
- Pilih data
- Insert → Chart
- Pilih jenis chart (Line, Bar, Pie)
- Customize sesuai kebutuhan
```

### Di Google Sheets

**1. Import File**
```
- Buka Google Sheets
- File → Import
- Upload → Select file
- Import location: New spreadsheet
- Separator type: Comma
- Click "Import data"
```

**2. Format Data**
```
- Sama seperti Excel
- Format → Number → Currency
- Set locale: Indonesia
```

**3. Share & Collaborate**
```
- Click "Share" button
- Add collaborators
- Set permissions
- Send link
```

## 🔧 Troubleshooting

### Kolom Tidak Terpisah

**Problem**: Semua data dalam satu kolom

**Solution**:
```
Excel:
1. Select kolom A
2. Data → Text to Columns
3. Delimited → Next
4. Check "Comma" → Next
5. Finish

Google Sheets:
1. Select kolom A
2. Data → Split text to columns
3. Separator: Comma
```

### Karakter Aneh (Encoding Issue)

**Problem**: Karakter Indonesia tidak tampil benar

**Solution**:
```
Excel:
1. Data → Get Data → From File → From Text/CSV
2. File Origin: UTF-8
3. Load

Google Sheets:
- Otomatis handle UTF-8
- Tidak perlu setting khusus
```

### Angka Jadi Tanggal

**Problem**: Excel convert angka jadi format tanggal

**Solution**:
```
1. Select kolom yang bermasalah
2. Format Cells → Number → Text
3. Atau tambahkan ' di depan angka
```

### File Tidak Ter-download

**Problem**: Klik export tapi tidak download

**Solution**:
```
1. Disable popup blocker
2. Check browser download settings
3. Try different browser
4. Clear browser cache
```

## 📈 Analisis Lanjutan

### Pivot Table

**Excel:**
```
1. Select data range
2. Insert → PivotTable
3. Drag fields ke Rows/Columns/Values
4. Analyze data
```

**Google Sheets:**
```
1. Select data range
2. Data → Pivot table
3. Add rows/columns/values
4. Customize
```

### Formula Berguna

**Total:**
```
=SUM(B2:B100)
```

**Rata-rata:**
```
=AVERAGE(B2:B100)
```

**Persentase:**
```
=B2/SUM($B$2:$B$100)*100
```

**Growth Rate:**
```
=(B2-B1)/B1*100
```

**Conditional Formatting:**
```
1. Select range
2. Home → Conditional Formatting
3. Color Scales atau Data Bars
4. Apply
```

## 🎯 Use Cases

### Monthly Report

**Steps:**
```
1. Export laporan penjualan bulan ini
2. Export laporan bulan lalu
3. Buka kedua file di Excel
4. Buat sheet baru untuk comparison
5. Hitung growth rate
6. Buat chart
7. Present ke owner
```

### Product Analysis

**Steps:**
```
1. Export laporan produk
2. Sort by Total Pendapatan (descending)
3. Identifikasi top 20% products
4. Calculate contribution percentage
5. Focus inventory pada top products
```

### Cashier Performance

**Steps:**
```
1. Export laporan kasir
2. Sort by Total Pendapatan
3. Calculate average per cashier
4. Identify top & bottom performers
5. Plan training/incentive
```

### Inventory Optimization

**Steps:**
```
1. Export laporan inventory
2. Filter by Status = "Menipis"
3. Cross-check dengan laporan produk
4. Prioritize reorder untuk best sellers
5. Plan clearance untuk slow movers
```

## 📊 Template Excel

### Dashboard Template

Buat dashboard Excel dengan:
- Summary cards (Total Revenue, Transactions, etc)
- Charts (Line chart untuk trend, Pie chart untuk breakdown)
- Tables (Top products, Top cashiers)
- Conditional formatting (Red untuk low, Green untuk high)

### Monthly Report Template

Struktur:
```
Sheet 1: Executive Summary
Sheet 2: Sales Report
Sheet 3: Product Report
Sheet 4: Cashier Report
Sheet 5: Charts & Graphs
```

## 🔐 Best Practices

### Security

- ✅ Jangan share file dengan data sensitif
- ✅ Password protect Excel file jika perlu
- ✅ Delete file setelah tidak digunakan
- ✅ Backup file penting

### Organization

- ✅ Naming convention: `[Type]_[Period]_[Date].csv`
- ✅ Folder structure: `Reports/2026/03/`
- ✅ Archive old reports
- ✅ Document insights

### Automation

- ✅ Schedule regular exports
- ✅ Create templates untuk analysis
- ✅ Automate repetitive tasks dengan macro
- ✅ Share templates dengan team

## 📞 Support

### Masalah Export

**Q**: Export button tidak muncul
**A**: Generate laporan dulu, baru export button muncul

**Q**: File corrupt atau tidak bisa dibuka
**A**: Try export ulang, atau gunakan browser lain

**Q**: Data tidak lengkap
**A**: Cek filter dan periode tanggal

### Contact

- Email: support@tokomakmur.com
- Telp: 0812-3456-7890

---

**Last Updated**: 2026-03-05
**Version**: 1.0.0