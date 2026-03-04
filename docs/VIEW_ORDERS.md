# 📦 Cara Melihat Pesanan (Orders)

## Lokasi Pesanan

Setelah customer melakukan checkout, pesanan akan tersimpan di:

### 1. Database
- **Tabel**: `orders`
- **Lokasi**: Database MySQL `ecomerce`
- **Struktur**:
  ```sql
  SELECT 
    id,
    customer_id,
    product_id,
    product_quantity,
    total_price,
    payment_method,
    status,
    created_at
  FROM orders
  ORDER BY created_at DESC;
  ```

### 2. Admin Panel Filament

**URL**: `http://localhost:8000/admin/orders`

**Login**:
- Email: `admin@example.com`
- Password: `password`

---

## 🖥️ Melihat Pesanan di Admin Panel

### Step 1: Login ke Admin Panel

1. Buka browser
2. Go to: `http://localhost:8000/admin`
3. Login dengan:
   - Email: `admin@example.com`
   - Password: `password`

### Step 2: Akses Menu Orders

1. Di sidebar kiri, cari menu **"Orders"** (ikon shopping bag)
2. Click menu **"Orders"**
3. Akan muncul list semua pesanan

### Step 3: Lihat List Orders

Anda akan melihat tabel dengan kolom:
- **ID** - Nomor pesanan
- **Customer** - Nama customer
- **Product** - Nama produk
- **Quantity** - Jumlah
- **Total Price** - Total harga
- **Payment Method** - Metode pembayaran (bca, mandiri, gopay, dll)
- **Status** - Status pesanan (pending, paid, processing, shipped, delivered)
- **Created At** - Tanggal pesanan dibuat

### Step 4: Filter & Search

**Filter by Status:**
- Click dropdown "Status"
- Pilih: Pending, Paid, Processing, Shipped, Delivered, Cancelled

**Search:**
- Ketik nama customer atau produk di search box
- Tekan Enter

**Sort:**
- Click header kolom untuk sort (ID, Total Price, Created At)

### Step 5: View Order Detail

1. Click row pesanan yang ingin dilihat
2. Atau click icon "View" (mata)
3. Akan muncul detail lengkap:
   - Customer info
   - Product info
   - Payment method
   - Status
   - Timestamps

### Step 6: Edit Order

1. Click icon "Edit" (pensil)
2. Anda bisa update:
   - Status pesanan
   - Payment method
   - Quantity (jika perlu)
3. Click "Save" untuk simpan perubahan

---

## 📊 Status Pesanan

### Status Flow

```
pending → paid → processing → shipped → delivered
                                    ↓
                              cancelled
```

### Status Descriptions

| Status | Deskripsi | Action |
|--------|-----------|--------|
| **pending** | Menunggu pembayaran | Customer belum transfer |
| **paid** | Sudah dibayar | Verifikasi pembayaran selesai |
| **processing** | Sedang diproses | Packing barang |
| **shipped** | Sudah dikirim | Barang dalam perjalanan |
| **delivered** | Sudah diterima | Pesanan selesai |
| **cancelled** | Dibatalkan | Pesanan dibatalkan |

---

## 🔄 Update Status Pesanan

### Manual Update via Admin Panel

1. Go to **Orders** menu
2. Click order yang ingin diupdate
3. Click **"Edit"**
4. Ubah field **"Status"**
5. Pilih status baru:
   - Pending → Paid (setelah verifikasi pembayaran)
   - Paid → Processing (mulai proses)
   - Processing → Shipped (sudah dikirim)
   - Shipped → Delivered (sudah diterima)
6. Click **"Save"**

### Bulk Update

1. Select multiple orders (checkbox)
2. Click **"Bulk Actions"**
3. Pilih action (Update Status, Delete, dll)
4. Confirm

---

## 🔍 Melihat Pesanan via Database

### Via Tinker (Command Line)

```bash
php artisan tinker
```

```php
// Get all orders
$orders = App\Models\Order::all();

// Get recent orders
$orders = App\Models\Order::latest()->take(10)->get();

// Get orders by status
$pending = App\Models\Order::where('status', 'pending')->get();

// Get orders with customer and product
$orders = App\Models\Order::with(['customer', 'product'])->get();

// Get orders by payment method
$bca = App\Models\Order::where('payment_method', 'bca')->get();

// Count orders
$total = App\Models\Order::count();
$pending = App\Models\Order::where('status', 'pending')->count();
```

### Via MySQL Client

```bash
mysql -u root -p ecomerce
```

```sql
-- View all orders
SELECT * FROM orders ORDER BY created_at DESC LIMIT 10;

-- View orders with customer and product names
SELECT 
    o.id,
    c.name as customer_name,
    p.product_name,
    o.product_quantity,
    o.total_price,
    o.payment_method,
    o.status,
    o.created_at
FROM orders o
JOIN customers c ON o.customer_id = c.id
JOIN products p ON o.product_id = p.id
ORDER BY o.created_at DESC;

-- Count orders by status
SELECT status, COUNT(*) as total 
FROM orders 
GROUP BY status;

-- Count orders by payment method
SELECT payment_method, COUNT(*) as total 
FROM orders 
GROUP BY payment_method;

-- Total revenue
SELECT SUM(total_price) as total_revenue FROM orders;

-- Revenue by status
SELECT status, SUM(total_price) as revenue 
FROM orders 
GROUP BY status;
```

---

## 📱 Melihat Pesanan Baru

### Real-time Monitoring

**Option 1: Refresh Admin Panel**
- Buka halaman Orders
- Refresh browser (F5)
- Pesanan baru akan muncul di atas

**Option 2: Auto-refresh**
- Buka Developer Tools (F12)
- Console tab
- Run:
  ```javascript
  setInterval(() => location.reload(), 30000); // Refresh every 30 seconds
  ```

**Option 3: Database Query**
```bash
# Watch for new orders
watch -n 5 'mysql -u root -p ecomerce -e "SELECT id, status, created_at FROM orders ORDER BY created_at DESC LIMIT 5;"'
```

---

## 🎯 Quick Access

### Direct URLs

- **All Orders**: `http://localhost:8000/admin/orders`
- **Create Order**: `http://localhost:8000/admin/orders/create`
- **Pending Orders**: `http://localhost:8000/admin/orders?tableFilters[status][value]=pending`
- **Today's Orders**: Filter by date in admin panel

### Keyboard Shortcuts (in Admin Panel)

- `Ctrl/Cmd + K` - Quick search
- `Ctrl/Cmd + /` - Focus search
- `Esc` - Close modal

---

## 📈 Order Statistics

### View in Dashboard

1. Go to **Dashboard** (`/admin`)
2. Lihat widgets:
   - Total Orders
   - Pending Orders
   - Completed Orders
   - Total Revenue

### Custom Reports

Go to **Reports** menu (jika ada) untuk:
- Daily sales report
- Monthly sales report
- Best selling products
- Revenue by payment method

---

## 🔔 Notifications

### Order Notifications (Future Feature)

Saat ini belum ada notifikasi otomatis. Untuk melihat pesanan baru:
1. Check admin panel secara berkala
2. Atau setup email notifications (future)
3. Atau setup webhook (future)

---

## 🛠️ Troubleshooting

### Issue: Orders tidak muncul di admin panel

**Solution 1**: Clear cache
```bash
php artisan optimize:clear
```

**Solution 2**: Check database
```bash
php artisan tinker
>>> App\Models\Order::count()
```

**Solution 3**: Check permissions
- Pastikan user login sebagai admin
- Check role & permissions

### Issue: Order detail tidak lengkap

**Solution**: Check relationships
```php
// In Order model
public function customer() {
    return $this->belongsTo(Customer::class);
}

public function product() {
    return $this->belongsTo(Product::class);
}
```

### Issue: Payment method tidak tersimpan

**Solution**: Check fillable
```php
// In Order model
protected $fillable = [
    'payment_method', // Make sure this exists
    // ...
];
```

---

## 📞 Support

Jika ada pertanyaan:
- Email: fatahgilang23@gmail.com
- WhatsApp: 082333058317

---

## 🎓 Tutorial Video (Coming Soon)

1. Login ke Admin Panel
2. Navigasi Menu Orders
3. Filter & Search Orders
4. Update Order Status
5. View Order Details
6. Export Orders to Excel

---

## ✅ Checklist

Setelah customer checkout, pastikan:
- [ ] Order masuk ke database (check via tinker)
- [ ] Order muncul di admin panel
- [ ] Payment method tersimpan
- [ ] Status = "pending"
- [ ] Stock produk berkurang
- [ ] Customer data tersimpan

---

**Last Updated**: 2026-03-04
**Version**: 1.0.0
