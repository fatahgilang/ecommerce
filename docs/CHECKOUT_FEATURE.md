# Fitur Checkout E-Commerce

## Overview

Fitur checkout memungkinkan pelanggan untuk menyelesaikan pembelian produk dari keranjang belanja. Sistem akan otomatis membuat pesanan (orders) dan mengupdate stok produk.

## Flow Checkout

```
Cart Page → Fill Customer Info → Submit → Create Orders → Update Stock → Success Message → Redirect Home
```

## Komponen

### 1. Frontend (Cart.vue)

**Lokasi**: `resources/js/Pages/Cart.vue`

**Fitur:**
- Tampilan keranjang belanja
- Form data pelanggan (nama, email, telepon, alamat)
- Ringkasan belanja (subtotal, ongkir, total)
- Validasi form
- Submit checkout
- Loading state
- Error handling

**Form Fields:**
```javascript
{
  customer_name: string (required),
  customer_email: email (required),
  customer_phone: string (required),
  customer_address: text (required),
}
```

### 2. Backend (CheckoutController)

**Lokasi**: `app/Http/Controllers/Frontend/CheckoutController.php`

**Method**: `process(Request $request)`

**Proses:**
1. Validasi data input
2. Create/Get customer berdasarkan email
3. Loop setiap item di cart:
   - Validasi stok produk
   - Hitung total harga
   - Create order
   - Update stok produk (decrement)
4. Commit transaction
5. Return response JSON

**Validation Rules:**
```php
[
    'customer_name' => 'required|string|max:255',
    'customer_email' => 'required|email|max:255',
    'customer_phone' => 'required|string|max:20',
    'customer_address' => 'required|string',
    'items' => 'required|array|min:1',
    'items.*.product_id' => 'required|exists:products,id',
    'items.*.quantity' => 'required|integer|min:1',
    'items.*.price' => 'required|numeric|min:0',
]
```

### 3. Route

**Lokasi**: `routes/web.php`

```php
Route::post('/checkout', [CheckoutController::class, 'process'])
    ->name('checkout.process');
```

## Database Changes

### Orders Table

Setiap item di cart akan membuat 1 record order:

```sql
INSERT INTO orders (
    customer_id,
    product_id,
    quantity,
    total_price,
    order_date,
    status,
    created_at,
    updated_at
) VALUES (?, ?, ?, ?, NOW(), 'pending', NOW(), NOW());
```

### Products Table

Stok produk akan dikurangi:

```sql
UPDATE products 
SET stock = stock - ? 
WHERE id = ?;
```

### Customers Table

Customer baru akan dibuat jika email belum ada:

```sql
INSERT INTO customers (
    name,
    email,
    phone,
    address,
    created_at,
    updated_at
) VALUES (?, ?, ?, ?, NOW(), NOW())
ON DUPLICATE KEY UPDATE
    name = VALUES(name),
    phone = VALUES(phone),
    address = VALUES(address);
```

## Request & Response

### Request Example

**Endpoint**: `POST /checkout`

**Headers**:
```
Content-Type: application/json
X-CSRF-TOKEN: {token}
```

**Body**:
```json
{
  "customer_name": "John Doe",
  "customer_email": "john@example.com",
  "customer_phone": "08123456789",
  "customer_address": "Jl. Contoh No. 123, Jakarta",
  "items": [
    {
      "product_id": 1,
      "quantity": 2,
      "price": 75000
    },
    {
      "product_id": 5,
      "quantity": 1,
      "price": 18000
    }
  ]
}
```

### Success Response

**Status**: `201 Created`

```json
{
  "success": true,
  "message": "Pesanan berhasil dibuat!",
  "data": {
    "customer": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone": "08123456789"
    },
    "orders": [
      {
        "order_id": 1,
        "product_name": "Beras Premium 5kg",
        "quantity": 2,
        "price": 75000,
        "total": 150000
      },
      {
        "order_id": 2,
        "product_name": "Susu UHT 1L",
        "quantity": 1,
        "price": 18000,
        "total": 18000
      }
    ],
    "subtotal": 168000,
    "total": 168000
  }
}
```

### Error Response

**Status**: `422 Unprocessable Entity`

```json
{
  "success": false,
  "message": "Gagal membuat pesanan: Stok produk Beras Premium 5kg tidak mencukupi. Tersedia: 1"
}
```

## Frontend Implementation

### Cart Component

```vue
<template>
  <form @submit.prevent="checkout">
    <!-- Customer Info Form -->
    <input v-model="checkoutForm.customer_name" required />
    <input v-model="checkoutForm.customer_email" type="email" required />
    <input v-model="checkoutForm.customer_phone" required />
    <textarea v-model="checkoutForm.customer_address" required></textarea>
    
    <!-- Submit Button -->
    <button type="submit" :disabled="isProcessing">
      {{ isProcessing ? 'Memproses...' : 'Proses Pesanan' }}
    </button>
  </form>
</template>

<script setup>
const checkout = async () => {
  isProcessing.value = true;
  
  try {
    const response = await axios.post('/checkout', {
      ...checkoutForm.value,
      items: cartItems.value.map(item => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.product_price,
      })),
    });
    
    if (response.data.success) {
      // Clear cart
      localStorage.removeItem('cart');
      
      // Show success message
      alert('Pesanan berhasil!');
      
      // Redirect
      router.visit('/');
    }
  } catch (error) {
    alert(error.response.data.message);
  } finally {
    isProcessing.value = false;
  }
};
</script>
```

## Security Features

### 1. CSRF Protection
- Laravel CSRF token otomatis divalidasi
- Axios automatically includes CSRF token

### 2. Input Validation
- Server-side validation untuk semua input
- Email format validation
- Product existence check
- Stock availability check

### 3. Database Transaction
- Menggunakan DB transaction
- Rollback jika ada error
- Atomic operations

### 4. Stock Management
- Check stock sebelum create order
- Decrement stock dalam transaction
- Prevent overselling

## Error Handling

### Frontend Errors

1. **Form Validation**
   ```javascript
   if (!checkoutForm.value.customer_name) {
     alert('Mohon lengkapi semua data!');
     return;
   }
   ```

2. **Network Error**
   ```javascript
   catch (error) {
     let errorMessage = 'Gagal memproses pesanan.';
     if (error.response?.data?.message) {
       errorMessage = error.response.data.message;
     }
     alert(errorMessage);
   }
   ```

### Backend Errors

1. **Validation Error**
   ```php
   $validated = $request->validate([...]);
   // Throws ValidationException if fails
   ```

2. **Stock Error**
   ```php
   if ($product->stock < $item['quantity']) {
     throw new \Exception("Stok tidak mencukupi");
   }
   ```

3. **Database Error**
   ```php
   try {
     DB::beginTransaction();
     // ... operations
     DB::commit();
   } catch (\Exception $e) {
     DB::rollBack();
     return response()->json(['error' => $e->getMessage()], 422);
   }
   ```

## Testing Checkout

### Manual Testing

1. **Add products to cart**
   - Browse products
   - Click "Tambah ke Keranjang"
   - Verify cart counter updates

2. **Go to cart**
   - Click cart icon
   - Verify items displayed
   - Test quantity increase/decrease
   - Test remove item

3. **Fill checkout form**
   - Enter customer name
   - Enter valid email
   - Enter phone number
   - Enter complete address

4. **Submit checkout**
   - Click "Proses Pesanan"
   - Wait for processing
   - Verify success message
   - Check cart is cleared

5. **Verify in admin**
   - Login to admin panel
   - Go to Orders
   - Verify new orders created
   - Check order status is "pending"
   - Verify product stock decreased

### Test Cases

#### Success Cases

1. ✅ Checkout with 1 item
2. ✅ Checkout with multiple items
3. ✅ Checkout with existing customer email
4. ✅ Checkout with new customer email
5. ✅ Stock decreases after checkout
6. ✅ Cart clears after checkout

#### Error Cases

1. ❌ Empty cart checkout
2. ❌ Missing customer info
3. ❌ Invalid email format
4. ❌ Product out of stock
5. ❌ Insufficient stock
6. ❌ Network error

### API Testing with cURL

```bash
# Successful checkout
curl -X POST http://localhost:8000/checkout \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: {token}" \
  -d '{
    "customer_name": "Test User",
    "customer_email": "test@example.com",
    "customer_phone": "08123456789",
    "customer_address": "Test Address",
    "items": [
      {
        "product_id": 1,
        "quantity": 1,
        "price": 75000
      }
    ]
  }'
```

## Admin Panel Integration

### View Orders

1. Login ke admin panel: `/admin`
2. Go to **Orders** menu
3. View list of all orders
4. Filter by status, date, customer
5. View order details

### Update Order Status

1. Click order to view details
2. Change status:
   - `pending` → `processing`
   - `processing` → `shipped`
   - `shipped` → `delivered`
   - Or `cancelled`
3. Save changes

### View Customer Info

1. Go to **Customers** menu
2. Search by email or name
3. View customer details
4. See order history

## Future Enhancements

### Phase 2
- [ ] Payment gateway integration
- [ ] Order tracking page
- [ ] Email notifications
- [ ] Invoice generation
- [ ] Multiple shipping addresses

### Phase 3
- [ ] Discount codes
- [ ] Loyalty points
- [ ] Wishlist
- [ ] Product reviews after purchase
- [ ] Reorder functionality

## Troubleshooting

### Issue: Stock not updating

**Solution**: Check database transaction is committed
```php
DB::commit(); // Make sure this is called
```

### Issue: Orders not created

**Solution**: Check validation errors
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log
```

### Issue: Cart not clearing

**Solution**: Check localStorage
```javascript
localStorage.removeItem('cart');
window.dispatchEvent(new CustomEvent('cart-updated', { 
  detail: { count: 0 }
}));
```

### Issue: CSRF token mismatch

**Solution**: Ensure CSRF token is included
```javascript
// Axios automatically includes it from meta tag
<meta name="csrf-token" content="{{ csrf_token() }}">
```

## Support

Untuk bantuan lebih lanjut:
- Email: fatahgilang23@gmail.com
- WhatsApp: 082333058317

## Changelog

### Version 1.0.0 (2026-03-03)
- ✅ Initial checkout implementation
- ✅ Customer creation/update
- ✅ Order creation
- ✅ Stock management
- ✅ Transaction handling
- ✅ Error handling
- ✅ Success notifications
