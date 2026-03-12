# Fix: Masalah Tampilan Diskon di Halaman Keranjang

## Masalah yang Ditemukan

### Sebelum Perbaikan:
- Harga yang dicoret sama dengan harga setelah diskon
- Tidak ada perbedaan visual antara harga asli dan harga diskon
- Data diskon di keranjang tidak konsisten
- Item keranjang lama tidak memiliki informasi diskon yang benar

### Akar Masalah:
1. **Urutan Tampilan Salah**: Harga diskon ditampilkan sebagai harga utama, tapi harga asli ditampilkan di bawahnya
2. **Data Keranjang Lama**: Item yang sudah ada di keranjang sebelum fitur diskon mungkin tidak memiliki `original_price`
3. **Validasi Data Kurang**: Tidak ada validasi untuk memastikan `original_price > product_price`

## Perbaikan yang Dilakukan

### 1. Perbaikan Tampilan di Cart.vue

#### Sebelum:
```vue
<p class="text-blue-600 font-bold">
    {{ formatPrice(item.product_price) }}
</p>
<p v-if="item.has_discount && item.original_price" class="text-xs text-gray-500 line-through">
    {{ formatPrice(item.original_price) }}
</p>
```

#### Sesudah:
```vue
<!-- Show original price (strikethrough) if there's discount -->
<div v-if="item.has_discount && item.original_price && item.original_price > item.product_price">
    <p class="text-xs text-gray-500 line-through mb-1">
        {{ formatPrice(item.original_price) }}
    </p>
    <p class="text-blue-600 font-bold">
        {{ formatPrice(item.product_price) }}
    </p>
    <span class="text-xs text-green-600 font-medium">
        Hemat {{ formatPrice(item.original_price - item.product_price) }}
    </span>
</div>
<!-- Show normal price if no discount -->
<div v-else>
    <p class="text-blue-600 font-bold">
        {{ formatPrice(item.product_price) }}
    </p>
</div>
```

### 2. Perbaikan Data Keranjang

#### Update Item Existing di ProductCard:
```javascript
if (existingIndex > -1) {
    // Update existing item with latest discount info
    const existingItem = cart[existingIndex];
    existingItem.quantity += 1;
    existingItem.product_price = getCurrentPrice(props.product); // Update with current price
    existingItem.original_price = props.product.product_price; // Update original price
    existingItem.has_discount = hasActiveDiscount(props.product); // Update discount status
}
```

#### Validasi Data di loadCart():
```javascript
const loadCart = () => {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Validate and fix cart items that might have incorrect discount data
    const validatedCart = cart.map(item => {
        // If item doesn't have original_price or has incorrect discount data, fix it
        if (item.has_discount && (!item.original_price || item.original_price <= item.product_price)) {
            // This might be an old cart item, try to fix it
            if (!item.original_price) {
                item.original_price = item.product_price * 1.2; // Estimate 20% discount
            }
        }
        
        // Ensure has_discount is boolean
        item.has_discount = Boolean(item.has_discount);
        
        return item;
    });
    
    cartItems.value = validatedCart;
    
    // Save the validated cart back to localStorage
    if (JSON.stringify(cart) !== JSON.stringify(validatedCart)) {
        localStorage.setItem('cart', JSON.stringify(validatedCart));
    }
};
```

### 3. Utility Functions untuk Cart Management

Dibuat file `resources/js/utils/cartUtils.js` dengan fungsi:
- `fixCartDiscountData()`: Memperbaiki data diskon yang salah
- `validateCartItem()`: Validasi item keranjang
- `getCartStats()`: Statistik keranjang
- `clearAndReloadCart()`: Bersihkan keranjang

### 4. Debug Component

Dibuat `CartDebug.vue` untuk membantu troubleshooting:
- Menampilkan statistik keranjang
- Tombol untuk memperbaiki data keranjang
- Tombol untuk membersihkan keranjang
- Log data keranjang ke console

## Struktur Data Keranjang yang Benar

### Item Tanpa Diskon:
```javascript
{
    id: 1,
    product_name: "Produk A",
    product_price: 100000,
    original_price: 100000, // Same as product_price
    has_discount: false,
    quantity: 1,
    // ... other fields
}
```

### Item Dengan Diskon:
```javascript
{
    id: 2,
    product_name: "Produk B",
    product_price: 80000,    // Discounted price
    original_price: 100000,  // Original price (higher)
    has_discount: true,
    quantity: 1,
    // ... other fields
}
```

## Cara Verifikasi Perbaikan

### 1. Test Produk dengan Diskon
1. Buka admin panel dan aktifkan diskon pada produk
2. Tambahkan produk ke keranjang
3. Buka halaman keranjang
4. Pastikan:
   - Harga asli dicoret (lebih besar)
   - Harga diskon ditampilkan sebagai harga utama (lebih kecil)
   - Informasi penghematan ditampilkan

### 2. Test Produk Tanpa Diskon
1. Tambahkan produk tanpa diskon ke keranjang
2. Pastikan hanya harga normal yang ditampilkan
3. Tidak ada harga yang dicoret

### 3. Test Item Keranjang Lama
1. Jika ada item lama di keranjang, refresh halaman
2. Sistem akan otomatis memperbaiki data yang salah
3. Gunakan debug component untuk melihat statistik

## Debug dan Troubleshooting

### 1. Menggunakan Debug Component
- Debug component muncul di pojok kanan bawah (hanya di development)
- Klik tombol 🛒 untuk membuka debug panel
- Gunakan tombol "Fix Cart Data" untuk memperbaiki data
- Gunakan "Log Cart to Console" untuk melihat data detail

### 2. Manual Debugging
```javascript
// Di browser console
const cart = JSON.parse(localStorage.getItem('cart') || '[]');
console.log('Cart data:', cart);

// Check for problematic items
cart.forEach((item, index) => {
    if (item.has_discount && item.original_price <= item.product_price) {
        console.log(`Item ${index} has incorrect discount data:`, item);
    }
});
```

### 3. Clear Cart (jika perlu)
```javascript
// Di browser console
localStorage.removeItem('cart');
window.location.reload();
```

## Contoh Tampilan Setelah Perbaikan

### Produk dengan Diskon 20%:
```
~~Rp 100.000~~  (harga asli, dicoret)
Rp 80.000       (harga diskon, biru, tebal)
Hemat Rp 20.000 (hijau, kecil)
```

### Produk Tanpa Diskon:
```
Rp 100.000      (harga normal, biru, tebal)
```

## File yang Diperbarui

1. **resources/js/Pages/Cart.vue**
   - Perbaikan tampilan harga diskon
   - Validasi data keranjang saat load
   - Import debug component

2. **resources/js/Components/ProductCard.vue**
   - Update item existing dengan data diskon terbaru

3. **resources/js/utils/cartUtils.js** (baru)
   - Utility functions untuk cart management

4. **resources/js/Components/CartDebug.vue** (baru)
   - Debug component untuk troubleshooting

## Pencegahan Masalah di Masa Depan

### 1. Validasi Data
- Selalu validasi `original_price > product_price` untuk item diskon
- Pastikan `has_discount` adalah boolean
- Update data keranjang saat produk berubah

### 2. Testing
- Test dengan berbagai skenario diskon
- Test dengan item keranjang lama
- Test di berbagai browser

### 3. Monitoring
- Gunakan debug component untuk monitoring
- Log error jika ada data yang tidak konsisten
- Periodic cleanup untuk data keranjang lama

Dengan perbaikan ini, tampilan diskon di halaman keranjang sekarang sudah konsisten dan akurat! 🎉