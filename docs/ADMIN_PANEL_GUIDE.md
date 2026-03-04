# 🎯 Panduan Admin Panel - Melihat Pesanan

## Quick Start

### 1️⃣ Login ke Admin Panel

```
URL: http://localhost:8000/admin
Email: admin@example.com
Password: password
```

### 2️⃣ Akses Menu Orders

Di sidebar kiri, click menu **"Orders"** (ikon 🛍️)

### 3️⃣ Lihat Pesanan

Semua pesanan akan ditampilkan dalam tabel.

---

## 📋 Struktur Menu Admin Panel

```
Dashboard
├── 📊 Dashboard (Home)
│
Manajemen Toko
├── 🏪 Shops (Toko)
├── 👥 Customers (Pelanggan)
├── 📦 Products (Produk)
├── 📁 Product Categories
│
Manajemen POS
├── 🛍️ Orders (PESANAN) ← DI SINI!
├── 💳 Transactions
├── 🧾 Cash Registers
├── 🏷️ Discounts
├── ⭐ Reviews
├── 🚚 Shipments
│
Pengaturan
└── 👤 Users
```

---

## 🛍️ Halaman Orders

### Tampilan Tabel Orders

| Kolom | Deskripsi | Contoh |
|-------|-----------|--------|
| **ID** | Nomor pesanan | #150 |
| **Customer** | Nama customer | Guest Customer |
| **Product** | Nama produk | Earphone Bluetooth |
| **Quantity** | Jumlah | 2 |
| **Total Price** | Total harga | Rp 700.000 |
| **Payment Method** | Metode bayar | BCA |
| **Status** | Status pesanan | pending |
| **Created At** | Tanggal | 04 Mar 2026 12:01 |
| **Actions** | Aksi | 👁️ View, ✏️ Edit, 🗑️ Delete |

### Filter & Search

**Filter by Status:**
```
[Dropdown Status] ▼
├── All
├── Pending (Menunggu pembayaran)
├── Paid (Sudah dibayar)
├── Processing (Sedang diproses)
├── Shipped (Sudah dikirim)
├── Delivered (Sudah diterima)
└── Cancelled (Dibatalkan)
```

**Search Box:**
```
🔍 [Search orders...] 
```
Bisa search by:
- Customer name
- Product name
- Order ID

**Date Filter:**
```
📅 [From Date] - [To Date]
```

---

## 👁️ View Order Detail

Click icon **"View"** (mata) untuk melihat detail:

### Order Information
```
Order #150
Status: Pending
Created: 04 Mar 2026 12:01
Updated: 04 Mar 2026 12:01
```

### Customer Information
```
Customer ID: 45
Name: Guest Customer
Email: guest_1709539260@example.com
Phone: -
Address: -
```

### Product Information
```
Product: Earphone Bluetooth
SKU: -
Price: Rp 350.000
Quantity: 2
Subtotal: Rp 700.000
```

### Payment Information
```
Payment Method: BCA
Account: 1234567890
Holder: Toko Makmur Jaya
Status: Pending
```

### Total
```
Subtotal: Rp 700.000
Shipping: Rp 0
Tax: Rp 0
─────────────────────
Total: Rp 700.000
```

---

## ✏️ Edit Order

Click icon **"Edit"** (pensil) untuk edit:

### Editable Fields

1. **Status** (Dropdown)
   - Pending
   - Paid
   - Processing
   - Shipped
   - Delivered
   - Cancelled

2. **Payment Method** (Optional)
   - BCA
   - Mandiri
   - BRI
   - GoPay
   - OVO
   - DANA

3. **Quantity** (Number)
   - Min: 1
   - Max: Stock available

4. **Notes** (Textarea)
   - Internal notes
   - Shipping notes

### Save Changes

Click **"Save"** button untuk simpan perubahan.

---

## 🔄 Update Status Workflow

### Typical Order Flow

```
1. Customer Checkout
   ↓
   Status: PENDING
   Action: Tunggu pembayaran

2. Customer Transfer
   ↓
   Status: PAID
   Action: Verifikasi pembayaran, update status

3. Admin Process Order
   ↓
   Status: PROCESSING
   Action: Packing barang

4. Admin Ship Order
   ↓
   Status: SHIPPED
   Action: Kirim barang, input resi

5. Customer Receive
   ↓
   Status: DELIVERED
   Action: Pesanan selesai
```

### How to Update Status

1. Go to **Orders** menu
2. Find order yang ingin diupdate
3. Click **"Edit"**
4. Change **"Status"** dropdown
5. Click **"Save"**

---

## 📊 Order Statistics

### Dashboard Widgets

Di halaman **Dashboard** (`/admin`), Anda akan melihat:

**Stats Overview:**
```
┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
│ Total Orders    │  │ Pending Orders  │  │ Total Revenue   │
│      89         │  │       1         │  │  Rp 15.2 Juta   │
└─────────────────┘  └─────────────────┘  └─────────────────┘
```

**Sales Chart:**
```
Revenue Trend (Last 7 Days)
│
│     ╱╲
│    ╱  ╲    ╱╲
│   ╱    ╲  ╱  ╲
│  ╱      ╲╱    ╲
│ ╱              ╲
└─────────────────────
  Mon Tue Wed Thu Fri
```

---

## 🔍 Advanced Search

### Search by Multiple Criteria

**Example Queries:**
```
# Search by customer name
"John Doe"

# Search by product name
"Earphone"

# Search by order ID
"#150"

# Search by payment method
"BCA"
```

### Combine Filters

1. Select **Status**: Pending
2. Select **Date Range**: Last 7 days
3. Enter **Search**: "Earphone"
4. Result: Pending orders for Earphone in last 7 days

---

## 📥 Export Orders

### Export to Excel/CSV

1. Go to **Orders** menu
2. Apply filters (optional)
3. Click **"Export"** button
4. Choose format:
   - Excel (.xlsx)
   - CSV (.csv)
   - PDF (.pdf)
5. Download file

### Export Data Includes:
- Order ID
- Customer name
- Product name
- Quantity
- Total price
- Payment method
- Status
- Date

---

## 🔔 Notifications (Future)

### Planned Features:

- [ ] Email notification saat order baru
- [ ] SMS notification untuk customer
- [ ] WhatsApp notification
- [ ] Push notification di browser
- [ ] Telegram bot integration

---

## 💡 Tips & Tricks

### 1. Quick Status Update
- Use keyboard shortcuts
- Bulk update multiple orders
- Set default status for new orders

### 2. Filter Presets
- Save common filters
- Quick access to pending orders
- Today's orders shortcut

### 3. Customer Communication
- Copy customer email/phone
- Send payment reminder
- Send shipping notification

### 4. Inventory Management
- Check stock before confirm order
- Auto-update stock after order
- Low stock alerts

---

## ⚠️ Important Notes

### Before Updating Status to "Paid"

✅ **Checklist:**
- [ ] Verify payment proof
- [ ] Check bank account
- [ ] Match amount with order total
- [ ] Confirm with customer

### Before Updating Status to "Shipped"

✅ **Checklist:**
- [ ] Product packed
- [ ] Shipping label created
- [ ] Tracking number ready
- [ ] Customer notified

### Before Cancelling Order

✅ **Checklist:**
- [ ] Confirm with customer
- [ ] Check refund policy
- [ ] Restore product stock
- [ ] Document reason

---

## 🆘 Common Issues

### Issue 1: Order tidak muncul

**Possible Causes:**
- Cache issue
- Database sync issue
- Permission issue

**Solutions:**
```bash
# Clear cache
php artisan optimize:clear

# Check database
php artisan tinker
>>> App\Models\Order::latest()->first()

# Refresh browser
Ctrl/Cmd + Shift + R
```

### Issue 2: Cannot update status

**Possible Causes:**
- Validation error
- Database constraint
- Permission denied

**Solutions:**
- Check error message
- Verify user permissions
- Check database logs

### Issue 3: Payment method tidak tersimpan

**Possible Causes:**
- Field not in fillable
- Validation error
- Frontend not sending data

**Solutions:**
```php
// Check Order model
protected $fillable = [
    'payment_method', // Add this
];
```

---

## 📞 Need Help?

**Contact Support:**
- 📧 Email: fatahgilang23@gmail.com
- 📱 WhatsApp: 082333058317

**Documentation:**
- 📖 [View Orders Guide](VIEW_ORDERS.md)
- 💳 [Payment Methods](PAYMENT_METHODS.md)
- 🛒 [Checkout Feature](CHECKOUT_FEATURE.md)

---

## ✅ Quick Reference

### URLs
```
Admin Login:  http://localhost:8000/admin
Orders List:  http://localhost:8000/admin/orders
Create Order: http://localhost:8000/admin/orders/create
Dashboard:    http://localhost:8000/admin
```

### Default Credentials
```
Email:    admin@example.com
Password: password
```

### Order Status
```
pending    → Menunggu pembayaran
paid       → Sudah dibayar
processing → Sedang diproses
shipped    → Sudah dikirim
delivered  → Sudah diterima
cancelled  → Dibatalkan
```

### Payment Methods
```
bca      → Bank BCA
mandiri  → Bank Mandiri
bri      → Bank BRI
gopay    → GoPay
ovo      → OVO
dana     → DANA
```

---

**Happy Managing! 🎉**
