# 📦 Manajemen Pesanan (Orders)

## Overview

Sistem pesanan dirancang agar pesanan **hanya bisa dibuat melalui checkout di frontend**. Admin panel hanya untuk melihat dan mengupdate status pesanan.

## Alur Pesanan

```
Customer (Frontend) → Checkout → Order Created → Admin View/Update Status
```

### ❌ Tidak Bisa:
- Create order manual di admin panel
- Add order via API tanpa checkout

### ✅ Bisa:
- View orders di admin panel
- Edit status pesanan
- Edit metode pembayaran
- Delete order (jika perlu)
- Filter & search orders

## Cara Membuat Pesanan

### Via Frontend (Customer)

1. **Browse Products**
   - URL: `http://localhost:8000/products`
   - Pilih produk yang diinginkan

2. **Add to Cart**
   - Click tombol "Tambah ke Keranjang"
   - Produk masuk ke cart

3. **Go to Cart**
   - Click icon cart di navbar
   - Review produk di cart

4. **Select Payment Method**
   - Pilih metode pembayaran:
     - Bank BCA
     - Bank Mandiri
     - Bank BRI
     - GoPay
     - OVO
     - DANA

5. **Checkout**
   - Click "Buat Pesanan"
   - Order otomatis dibuat dengan status "pending"

6. **Payment**
   - Customer transfer ke rekening toko
   - Simpan bukti transfer
   - Hubungi toko untuk konfirmasi

## Admin Panel - Manajemen Pesanan

### Akses Menu Orders

```
URL: http://localhost:8000/admin/orders
Login: admin@example.com / password
```

### Fitur yang Tersedia

#### 1. View Orders List

**Kolom yang Ditampilkan:**
- ID Pesanan (#123)
- Pelanggan (nama customer)
- Produk (nama produk)
- Jumlah (quantity)
- Total Harga (Rp format)
- Metode Pembayaran (badge dengan warna)
- Status (badge dengan warna)
- Tanggal Pesanan

**Default Sort:** Terbaru dulu (created_at DESC)

#### 2. Filter Orders

**Filter by Status:**
- Menunggu Pembayaran (pending)
- Sudah Dibayar (paid)
- Diproses (processing)
- Dikirim (shipped)
- Selesai (delivered)
- Dibatalkan (cancelled)

**Filter by Payment Method:**
- BCA
- Mandiri
- BRI
- GoPay
- OVO
- DANA

#### 3. Search Orders

Search by:
- ID Pesanan
- Nama Pelanggan
- Nama Produk
- Metode Pembayaran

#### 4. View Order Detail

Click icon "View" (mata) untuk melihat detail lengkap:
- Informasi customer
- Informasi produk
- Jumlah & harga
- Metode pembayaran
- Status pesanan
- Tanggal dibuat

#### 5. Edit Order

Click icon "Edit" (pensil) untuk update:

**Yang Bisa Diubah:**
- Status pesanan
- Metode pembayaran

**Yang Tidak Bisa Diubah:**
- Customer
- Produk
- Jumlah
- Total harga

**Alasan:** Data pesanan harus sesuai dengan yang di-checkout customer.

#### 6. Delete Order

Click icon "Delete" (trash) untuk hapus order.

**Warning:** Hati-hati saat delete, stock produk tidak akan dikembalikan otomatis.

## Status Pesanan

### Status Flow

```
pending → paid → processing → shipped → delivered
                                    ↓
                              cancelled
```

### Status Descriptions

| Status | Badge Color | Deskripsi | Action Admin |
|--------|-------------|-----------|--------------|
| **pending** | Warning (Yellow) | Menunggu pembayaran dari customer | Tunggu konfirmasi pembayaran |
| **paid** | Info (Blue) | Customer sudah transfer, menunggu verifikasi | Verifikasi bukti transfer |
| **processing** | Primary (Purple) | Pesanan sedang diproses/packing | Packing barang |
| **shipped** | Success (Green) | Barang sudah dikirim | Input resi pengiriman |
| **delivered** | Success (Green) | Barang sudah diterima customer | Pesanan selesai |
| **cancelled** | Danger (Red) | Pesanan dibatalkan | Refund jika sudah bayar |

### Update Status

1. Go to Orders menu
2. Find order yang ingin diupdate
3. Click "Edit"
4. Change "Status Pesanan" dropdown
5. Select new status
6. Click "Save"

### Best Practices

**pending → paid:**
- Verifikasi bukti transfer
- Cek nominal sesuai total pesanan
- Cek rekening tujuan benar
- Konfirmasi dengan customer

**paid → processing:**
- Cek stock produk tersedia
- Mulai packing barang
- Siapkan label pengiriman

**processing → shipped:**
- Barang sudah dipacking
- Sudah diserahkan ke kurir
- Input nomor resi (future feature)
- Notifikasi customer (future feature)

**shipped → delivered:**
- Customer konfirmasi sudah terima
- Atau tracking menunjukkan delivered
- Pesanan selesai

**Any → cancelled:**
- Konfirmasi dengan customer
- Jika sudah bayar, proses refund
- Restore stock produk (manual)
- Document alasan pembatalan

## Payment Methods

### Transfer Bank

| Bank | Nomor Rekening | Atas Nama |
|------|----------------|-----------|
| BCA | 1234567890 | Toko Makmur Jaya |
| Mandiri | 0987654321 | Toko Makmur Jaya |
| BRI | 5555666677 | Toko Makmur Jaya |

### E-Wallet

| E-Wallet | Nomor HP | Atas Nama |
|----------|----------|-----------|
| GoPay | 081234567890 | Toko Makmur Jaya |
| OVO | 081234567890 | Toko Makmur Jaya |
| DANA | 081234567890 | Toko Makmur Jaya |

## Verifikasi Pembayaran

### Langkah-langkah

1. **Customer menghubungi**
   - Via WhatsApp/Email
   - Kirim bukti transfer
   - Sertakan ID Pesanan

2. **Admin cek bukti transfer**
   - Nominal sesuai?
   - Rekening tujuan benar?
   - Tanggal transfer valid?
   - Nama pengirim (optional)

3. **Update status**
   - Login admin panel
   - Find order by ID
   - Edit status: pending → paid
   - Save

4. **Konfirmasi ke customer**
   - "Pembayaran sudah diterima"
   - "Pesanan sedang diproses"
   - "Estimasi pengiriman: X hari"

## Reports & Statistics

### View Statistics

Go to Dashboard untuk melihat:
- Total Orders
- Pending Orders
- Completed Orders
- Total Revenue

### Export Orders

1. Go to Orders menu
2. Apply filters (optional)
3. Click "Export" button (future feature)
4. Download Excel/CSV

## Troubleshooting

### Issue: Order tidak muncul di admin panel

**Solution:**
```bash
# Clear cache
php artisan optimize:clear

# Refresh browser
Ctrl+Shift+R
```

### Issue: Tidak bisa create order

**Expected:** Tombol "New Order" tidak ada.

**Reason:** Orders hanya bisa dibuat via frontend checkout.

**Solution:** Instruksikan customer untuk checkout di frontend.

### Issue: Status tidak bisa diupdate

**Solution:**
1. Check user permissions
2. Try refresh page
3. Check Laravel logs

### Issue: Payment method tidak tersimpan

**Solution:**
Check Order model fillable:
```php
protected $fillable = [
    'payment_method',
    // ...
];
```

## API Access (Future)

### Get Orders

```bash
GET /api/orders
```

### Get Order by ID

```bash
GET /api/orders/{id}
```

### Update Order Status

```bash
PATCH /api/orders/{id}/status
{
  "status": "paid"
}
```

## Security

### Permissions

- **Admin:** Full access (view, edit, delete)
- **Staff:** View and edit only
- **Customer:** No access to admin panel

### Audit Trail

All order updates are logged:
- Who updated
- What changed
- When updated

Check `updated_at` timestamp for last update.

## Best Practices

### DO ✅

- Verifikasi pembayaran sebelum update status
- Konfirmasi dengan customer sebelum cancel
- Document alasan pembatalan
- Update status secara berkala
- Respond customer inquiry cepat

### DON'T ❌

- Jangan create order manual di database
- Jangan delete order tanpa alasan jelas
- Jangan update status tanpa verifikasi
- Jangan lupa konfirmasi ke customer
- Jangan abaikan pending orders

## Future Enhancements

### Phase 2
- [ ] Auto payment verification (payment gateway)
- [ ] Email notifications
- [ ] SMS notifications
- [ ] Shipping tracking integration
- [ ] Invoice generation
- [ ] Receipt printing

### Phase 3
- [ ] Refund management
- [ ] Return/exchange orders
- [ ] Order notes/comments
- [ ] Customer order history
- [ ] Reorder functionality

## Support

Untuk bantuan:
- Email: fatahgilang23@gmail.com
- WhatsApp: 082333058317

## Changelog

### Version 2.0.0 (2026-03-04)
- ✅ Disabled manual order creation
- ✅ Orders only via frontend checkout
- ✅ Improved order list UI
- ✅ Added status badges
- ✅ Added payment method badges
- ✅ Added filters
- ✅ Added view order detail
- ✅ Improved edit form
- ✅ Added subheading info
