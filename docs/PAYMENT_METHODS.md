# Metode Pembayaran E-Commerce

## Overview

Sistem checkout menggunakan metode pembayaran manual melalui transfer bank atau e-wallet. Customer memilih metode pembayaran, membuat pesanan, lalu melakukan transfer manual ke rekening toko.

## Flow Checkout

```
Cart → Pilih Metode Pembayaran → Buat Pesanan → Lihat Instruksi Pembayaran → Transfer Manual → Konfirmasi ke Toko
```

## Metode Pembayaran Tersedia

### Transfer Bank

1. **Bank BCA**
   - No. Rekening: 1234567890
   - Atas Nama: Toko Makmur Jaya

2. **Bank Mandiri**
   - No. Rekening: 0987654321
   - Atas Nama: Toko Makmur Jaya

3. **Bank BRI**
   - No. Rekening: 5555666677
   - Atas Nama: Toko Makmur Jaya

### E-Wallet

4. **GoPay**
   - No. HP: 081234567890
   - Atas Nama: Toko Makmur Jaya

5. **OVO**
   - No. HP: 081234567890
   - Atas Nama: Toko Makmur Jaya

6. **DANA**
   - No. HP: 081234567890
   - Atas Nama: Toko Makmur Jaya

## Implementasi Frontend

### Cart.vue

```vue
<template>
  <!-- Payment Method Selection -->
  <div class="space-y-2">
    <label 
      v-for="method in paymentMethods"
      :key="method.id"
      class="flex items-start p-3 border-2 rounded-lg cursor-pointer"
      :class="selectedPayment === method.id ? 'border-blue-600 bg-blue-50' : 'border-gray-200'"
    >
      <input
        type="radio"
        :value="method.id"
        v-model="selectedPayment"
      />
      <div class="ml-3">
        <span class="font-medium">{{ method.name }}</span>
        <p class="text-sm text-gray-600">{{ method.account }}</p>
        <p class="text-xs text-gray-500">a.n. {{ method.holder }}</p>
      </div>
    </label>
  </div>

  <!-- Checkout Button -->
  <button
    @click="checkout"
    :disabled="!selectedPayment || isProcessing"
  >
    Buat Pesanan
  </button>
</template>

<script setup>
const paymentMethods = ref([
  {
    id: 'bca',
    name: 'Bank BCA',
    type: 'Transfer Bank',
    account: '1234567890',
    holder: 'Toko Makmur Jaya'
  },
  // ... other methods
]);

const selectedPayment = ref('');

const checkout = async () => {
  const response = await axios.post('/checkout', {
    payment_method: selectedPayment.value,
    items: cartItems.value.map(item => ({
      product_id: item.id,
      quantity: item.quantity,
      price: item.product_price,
    })),
  });
  
  // Show payment instructions
  alert(`Transfer ke: ${paymentMethod.account}`);
};
</script>
```

## Implementasi Backend

### CheckoutController.php

```php
public function process(Request $request)
{
    $validated = $request->validate([
        'payment_method' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        // Create guest customer
        $customer = Customer::create([
            'name' => 'Guest Customer',
            'email' => 'guest_' . time() . '@example.com',
            'phone' => '-',
            'address' => '-',
        ]);

        // Create orders
        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            
            // Check stock
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Stok tidak mencukupi");
            }

            // Create order
            Order::create([
                'customer_id' => $customer->id,
                'product_id' => $product->id,
                'product_quantity' => $item['quantity'],
                'total_price' => $item['price'] * $item['quantity'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
            ]);

            // Update stock
            $product->decrement('stock', $item['quantity']);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat!',
            'data' => [...]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 422);
    }
}
```

## Database Schema

### Orders Table

```sql
CREATE TABLE orders (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    customer_id BIGINT,
    product_id BIGINT,
    product_quantity INT,
    total_price DECIMAL(10,2),
    payment_method VARCHAR(255),
    status VARCHAR(50) DEFAULT 'pending',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Status Values:**
- `pending` - Menunggu pembayaran
- `paid` - Sudah dibayar
- `processing` - Sedang diproses
- `shipped` - Sudah dikirim
- `delivered` - Sudah diterima
- `cancelled` - Dibatalkan

## Instruksi Pembayaran untuk Customer

### Setelah Checkout

Customer akan menerima alert dengan informasi:

```
✅ Pesanan Berhasil Dibuat!

ID Pesanan: #123

Produk:
- Beras Premium 5kg x2: Rp 150.000
- Susu UHT 1L x1: Rp 18.000

Total Pembayaran: Rp 168.000

💳 Metode Pembayaran: Bank BCA
📱 1234567890
👤 a.n. Toko Makmur Jaya

Silakan transfer sesuai nominal dan simpan bukti transfer.
Hubungi toko untuk konfirmasi pembayaran.

Terima kasih telah berbelanja!
```

### Langkah-langkah Pembayaran

1. **Transfer sesuai nominal**
   - Transfer tepat sesuai total pembayaran
   - Jangan lebih atau kurang

2. **Simpan bukti transfer**
   - Screenshot atau foto bukti transfer
   - Pastikan terlihat jelas

3. **Konfirmasi pembayaran**
   - Hubungi toko via WhatsApp/Email
   - Kirim bukti transfer
   - Sertakan ID Pesanan

4. **Tunggu konfirmasi**
   - Admin akan verifikasi pembayaran
   - Status pesanan akan diupdate
   - Pesanan akan diproses

## Admin Panel - Verifikasi Pembayaran

### Melihat Pesanan Baru

1. Login ke admin panel: `/admin`
2. Go to **Orders** menu
3. Filter by status: `pending`
4. Lihat list pesanan yang menunggu pembayaran

### Verifikasi Pembayaran

1. Customer menghubungi dan kirim bukti transfer
2. Admin cek bukti transfer
3. Cocokkan dengan pesanan:
   - ID Pesanan
   - Total pembayaran
   - Metode pembayaran
4. Jika valid, update status:
   - `pending` → `paid`
5. Proses pesanan:
   - `paid` → `processing`
6. Kirim pesanan:
   - `processing` → `shipped`
7. Pesanan diterima:
   - `shipped` → `delivered`

### Update Status Order

```php
// Via admin panel
$order = Order::find($orderId);
$order->status = 'paid';
$order->save();
```

## Konfigurasi Metode Pembayaran

### Update Rekening Bank

Edit file: `resources/js/Pages/Cart.vue`

```javascript
const paymentMethods = ref([
    {
        id: 'bca',
        name: 'Bank BCA',
        type: 'Transfer Bank',
        account: '1234567890', // Ganti dengan nomor rekening asli
        holder: 'Toko Makmur Jaya' // Ganti dengan nama pemilik rekening
    },
    // ... tambah atau edit metode lain
]);
```

### Menambah Metode Baru

```javascript
{
    id: 'bni', // ID unik
    name: 'Bank BNI', // Nama bank/e-wallet
    type: 'Transfer Bank', // Tipe: Transfer Bank atau E-Wallet
    account: '9999888877', // Nomor rekening/HP
    holder: 'Toko Makmur Jaya' // Nama pemilik
}
```

### Menonaktifkan Metode

Hapus atau comment out metode yang tidak digunakan:

```javascript
// {
//     id: 'gopay',
//     name: 'GoPay',
//     ...
// },
```

## Testing

### Manual Testing

1. **Add products to cart**
2. **Go to cart page**
3. **Select payment method**
4. **Click "Buat Pesanan"**
5. **Verify alert shows payment instructions**
6. **Check admin panel**:
   - Order created with status `pending`
   - Payment method saved
   - Stock decreased

### Test Cases

#### Success Cases
- ✅ Select BCA and checkout
- ✅ Select Mandiri and checkout
- ✅ Select GoPay and checkout
- ✅ Multiple items with different payment methods
- ✅ Payment instructions displayed correctly

#### Error Cases
- ❌ Checkout without selecting payment method
- ❌ Checkout with out of stock product
- ❌ Network error during checkout

## Security Considerations

### 1. No Sensitive Data Storage
- Tidak menyimpan nomor kartu kredit
- Tidak menyimpan PIN/password
- Hanya menyimpan metode pembayaran yang dipilih

### 2. Manual Verification
- Admin verifikasi manual setiap pembayaran
- Cek bukti transfer sebelum proses
- Prevent fraud dengan verifikasi

### 3. Order Tracking
- Setiap order punya ID unik
- Status tracking lengkap
- Audit trail di database

## Future Enhancements

### Phase 2
- [ ] Payment gateway integration (Midtrans, Xendit)
- [ ] Automatic payment verification
- [ ] QR Code payment
- [ ] Virtual account generation

### Phase 3
- [ ] Installment payment
- [ ] COD (Cash on Delivery)
- [ ] Credit card payment
- [ ] International payment (PayPal, Stripe)

## Troubleshooting

### Issue: Payment method not saved

**Solution**: Check Order model fillable
```php
protected $fillable = [
    'payment_method', // Make sure this is included
    // ...
];
```

### Issue: Alert not showing payment info

**Solution**: Check paymentMethods array
```javascript
const paymentMethod = paymentMethods.value.find(
    m => m.id === selectedPayment.value
);
```

### Issue: Order created but stock not updated

**Solution**: Check transaction commit
```php
DB::commit(); // Ensure this is called
```

## Support

Untuk bantuan:
- Email: fatahgilang23@gmail.com
- WhatsApp: 082333058317

## Changelog

### Version 2.0.0 (2026-03-04)
- ✅ Removed customer form
- ✅ Added payment method selection
- ✅ Added payment instructions
- ✅ Simplified checkout flow
- ✅ Guest customer support
