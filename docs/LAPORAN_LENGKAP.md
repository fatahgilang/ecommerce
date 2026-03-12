# 📊 Sistem Laporan Lengkap - Toko Makmur

## Overview

Sistem laporan komprehensif yang menyediakan berbagai jenis laporan untuk analisis bisnis dan pengambilan keputusan.

## 🎯 Jenis Laporan

### 1. Laporan Penjualan (Sales Report)
**Tujuan**: Analisis performa penjualan

**Informasi yang Disajikan**:
- Total pendapatan periode
- Jumlah transaksi
- Rata-rata nilai transaksi
- Rata-rata pendapatan harian
- Breakdown per metode pembayaran
- Grafik penjualan (per hari/minggu/bulan)

**Use Case**:
- Evaluasi performa penjualan
- Identifikasi trend penjualan
- Perencanaan target penjualan
- Analisis metode pembayaran populer

### 2. Laporan Produk (Product Report)
**Tujuan**: Analisis performa produk

**Informasi yang Disajikan**:
- Total produk terjual
- Total pendapatan per produk
- Top 10 produk terlaris
- Produk dengan stok menipis
- Jumlah produk out of stock
- Breakdown per kategori

**Use Case**:
- Identifikasi produk best seller
- Deteksi produk slow moving
- Perencanaan inventory
- Optimasi product mix

### 3. Laporan Kasir (Cashier Report)
**Tujuan**: Evaluasi performa kasir

**Informasi yang Disajikan**:
- Jumlah transaksi per kasir
- Total pendapatan per kasir
- Rata-rata transaksi per kasir
- Breakdown harian per kasir
- Ranking kasir

**Use Case**:
- Evaluasi performa karyawan
- Insentif berdasarkan performa
- Identifikasi training needs
- Penjadwalan shift optimal

### 4. Laporan Kas (Cash Flow Report)
**Tujuan**: Monitoring arus kas

**Informasi yang Disajikan**:
- Total pendapatan
- Breakdown tunai vs non-tunai
- Persentase per metode pembayaran
- Arus kas harian
- Trend pembayaran

**Use Case**:
- Monitoring cash on hand
- Perencanaan cash flow
- Rekonsiliasi kas
- Analisis metode pembayaran

### 5. Laporan Laba Rugi (Profit & Loss)
**Tujuan**: Analisis profitabilitas

**Informasi yang Disajikan**:
- Pendapatan kotor
- Diskon yang diberikan
- Pendapatan bersih
- COGS (Cost of Goods Sold)
- Biaya operasional
- Laba kotor & margin
- Laba bersih & margin

**Use Case**:
- Evaluasi profitabilitas
- Perencanaan budget
- Analisis margin
- Keputusan pricing

### 6. Laporan Inventory
**Tujuan**: Monitoring stok

**Informasi yang Disajikan**:
- Total produk
- Total stok (qty & value)
- Produk stok habis
- Produk stok menipis
- Breakdown per kategori
- Detail per produk

**Use Case**:
- Monitoring stok real-time
- Identifikasi dead stock
- Perencanaan order
- Optimasi inventory value

## 📱 Akses Laporan

### Via Admin Panel
```
1. Login ke /admin
2. Menu "Laporan" di sidebar
3. Pilih jenis laporan
4. Set tanggal periode (jika perlu)
5. Klik "Generate Laporan"
6. Lihat hasil
7. Export CSV (opsional)
```

### Via API
**Base URL**: `/api/v1/reports`

**Endpoints**:
```
GET /api/v1/reports/sales?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD&group_by=day
GET /api/v1/reports/products?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
GET /api/v1/reports/cashiers?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
GET /api/v1/reports/cashflow?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
GET /api/v1/reports/profitloss?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD
GET /api/v1/reports/inventory
```

## 📊 Response Examples

### Sales Report
```json
{
  "period": {
    "start": "2026-03-01",
    "end": "2026-03-05",
    "days": 5
  },
  "summary": {
    "total_revenue": 135880160.1,
    "total_transactions": 50,
    "average_transaction": 2717603.2,
    "daily_average": 22646693.35
  },
  "payment_methods": {
    "cash": {
      "count": 20,
      "total": 50000000
    },
    "bca": {
      "count": 15,
      "total": 45000000
    }
  },
  "chart_data": [...]
}
```

### Product Report
```json
{
  "period": {
    "start": "2026-03-01",
    "end": "2026-03-05"
  },
  "summary": {
    "total_products_sold": 500,
    "total_revenue": 135880160.1,
    "unique_products": 25,
    "out_of_stock_count": 2
  },
  "top_products": [...],
  "all_products": [...],
  "low_stock": [...]
}
```

## 💡 Best Practices

### Frekuensi Review

**Harian**:
- Laporan Penjualan (cek performa hari ini)
- Laporan Kas (rekonsiliasi kas)
- Laporan Inventory (cek stok kritis)

**Mingguan**:
- Laporan Produk (identifikasi trend)
- Laporan Kasir (evaluasi performa)

**Bulanan**:
- Laporan Laba Rugi (evaluasi profitabilitas)
- Laporan Inventory (full audit)
- Semua laporan untuk analisis komprehensif

### Tips Analisis

**Laporan Penjualan**:
- Bandingkan dengan periode sebelumnya
- Identifikasi hari/jam peak
- Analisis trend metode pembayaran
- Set target berdasarkan historical data

**Laporan Produk**:
- Focus pada top 20% produk (80/20 rule)
- Identifikasi produk slow moving untuk promo
- Monitor stok produk best seller
- Evaluasi product mix secara berkala

**Laporan Kasir**:
- Berikan feedback berdasarkan data
- Set target realistis per kasir
- Identifikasi best practices dari top performer
- Training untuk kasir dengan performa rendah

**Laporan Kas**:
- Rekonsiliasi harian untuk tunai
- Monitor trend pembayaran non-tunai
- Pastikan cash on hand cukup
- Analisis untuk perencanaan cash flow

**Laporan Laba Rugi**:
- Monitor margin secara konsisten
- Identifikasi cost yang bisa dioptimasi
- Bandingkan dengan target/budget
- Adjust pricing jika margin terlalu rendah

**Laporan Inventory**:
- Prioritaskan produk stok kritis
- Evaluasi dead stock untuk clearance
- Optimasi inventory value
- Balance antara availability dan cash flow

## 🔧 Fitur Export

### Export ke CSV
```
1. Generate laporan
2. Klik "Export CSV"
3. File otomatis download
4. Buka di Excel/Google Sheets
5. Analisis lebih lanjut
```

### Format CSV
- Header row dengan nama kolom
- Data rows dengan nilai
- Format angka: tanpa separator (untuk Excel)
- Encoding: UTF-8

## 📈 Integrasi dengan Fitur Lain

### AI Stock Prediction
- Laporan Produk → Input untuk prediksi
- Laporan Inventory → Validasi prediksi
- Laporan Penjualan → Trend analysis

### Dashboard
- Summary dari semua laporan
- Quick insights
- Alert & notifications

### Thermal Printer
- Print laporan harian
- Print summary untuk owner
- Print performance report untuk kasir

## 🎯 KPI yang Bisa Dimonitor

### Revenue KPIs
- Total Revenue
- Daily Average Revenue
- Revenue Growth Rate
- Revenue per Transaction

### Product KPIs
- Best Seller Products
- Slow Moving Products
- Stock Turnover Rate
- Out of Stock Rate

### Operational KPIs
- Transaction Count
- Average Transaction Value
- Cashier Performance
- Payment Method Distribution

### Profitability KPIs
- Gross Profit Margin
- Net Profit Margin
- COGS Percentage
- Discount Rate

### Inventory KPIs
- Total Stock Value
- Stock Availability Rate
- Low Stock Items
- Dead Stock Value

## 📊 Dashboard Metrics

### Daily Metrics
```
Today's Revenue: Rp XXX,XXX
Today's Transactions: XXX
Today's Average: Rp XXX,XXX
Cash on Hand: Rp XXX,XXX
```

### Weekly Metrics
```
Week Revenue: Rp XXX,XXX
Week Growth: +X%
Top Product: Product Name
Top Cashier: Cashier Name
```

### Monthly Metrics
```
Month Revenue: Rp XXX,XXX
Month Profit: Rp XXX,XXX
Profit Margin: XX%
Stock Value: Rp XXX,XXX
```

## 🔐 Access Control

### Role-Based Access
- **Owner**: Semua laporan
- **Manager**: Semua kecuali Laba Rugi
- **Kasir**: Hanya laporan sendiri
- **Admin**: Semua laporan

### Data Privacy
- Laporan kasir: hanya performa, bukan detail transaksi
- Laporan laba rugi: hanya untuk owner/manager
- Export: log untuk audit trail

## 🆘 Troubleshooting

### Laporan Kosong
**Problem**: Tidak ada data

**Solution**:
- Cek periode tanggal
- Pastikan ada transaksi di periode tersebut
- Cek filter yang diterapkan

### Data Tidak Akurat
**Problem**: Angka tidak sesuai

**Solution**:
- Clear cache: `php artisan optimize:clear`
- Cek data source (transactions, orders)
- Validasi perhitungan manual

### Export Gagal
**Problem**: CSV tidak ter-download

**Solution**:
- Cek browser settings
- Disable popup blocker
- Try different browser
- Cek server logs

## 📞 Support

### Documentation
- Full docs: `docs/LAPORAN_LENGKAP.md`
- API docs: `docs/API_DOCUMENTATION.md`

### Contact
- Email: support@tokomakmur.com
- Telp: 0812-3456-7890

---

**Last Updated**: 2026-03-05
**Version**: 1.0.0
**Status**: ✅ PRODUCTION READY