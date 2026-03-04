# 🧪 Test Checkout dari Browser

## Persiapan

1. **Pastikan server running**
   ```bash
   php artisan serve
   ```

2. **Pastikan assets sudah di-build**
   ```bash
   npm run build
   ```

3. **Clear cache**
   ```bash
   php artisan optimize:clear
   ```

## Step-by-Step Test

### 1. Buka Homepage
```
URL: http://localhost:8000
```

### 2. Browse Products
- Click menu "Products" atau
- Scroll ke bawah lihat featured products
- Click salah satu produk

### 3. Add to Cart
- Click tombol "Tambah ke Keranjang"
- Lihat cart counter di navbar bertambah
- Ulangi untuk produk lain (optional)

### 4. Go to Cart
- Click icon cart di navbar (🛒)
- Atau go to: `http://localhost:8000/cart`

### 5. Review Cart
- Lihat produk yang sudah ditambahkan
- Adjust quantity jika perlu (+/-)
- Remove item jika perlu

### 6. Pilih Metode Pembayaran
- Scroll ke bagian "Pilih Metode Pembayaran"
- Click salah satu metode:
  - Bank BCA
  - Bank Mandiri
  - Bank BRI
  - GoPay
  - OVO
  - DANA

### 7. Checkout
- Click tombol "Buat Pesanan"
- Tunggu proses (loading...)
- Alert akan muncul dengan detail pesanan

### 8. Verify Order Created
- Alert akan menampilkan:
  - ID Pesanan
  - Detail produk
  - Total pembayaran
  - Info rekening/e-wallet
- Click OK

### 9. Check Admin Panel
- Buka tab baru
- Go to: `http://localhost:8000/admin`
- Login:
  - Email: `admin@example.com`
  - Password: `password`
- Click menu "Orders"
- Lihat pesanan baru di list (paling atas)

## Troubleshooting

### Issue: Tombol "Buat Pesanan" disabled

**Cause**: Belum pilih metode pembayaran

**Solution**: Pilih salah satu metode pembayaran (radio button)

### Issue: Alert error "CSRF token mismatch"

**Cause**: CSRF token tidak valid

**Solution**:
1. Hard refresh browser: `Ctrl+Shift+R` (Windows) atau `Cmd+Shift+R` (Mac)
2. Clear browser cache
3. Restart server: `php artisan serve`

### Issue: Alert error "Gagal memproses pesanan"

**Cause**: Bisa berbagai hal

**Solution**:
1. Buka Developer Tools (F12)
2. Go to Console tab
3. Lihat error message
4. Check Network tab untuk melihat response

### Issue: Order tidak muncul di admin panel

**Cause**: Order belum ter-create

**Solution**:
1. Check browser console untuk error
2. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```
3. Check database:
   ```bash
   php artisan tinker
   >>> App\Models\Order::latest()->first()
   ```

### Issue: Stock tidak berkurang

**Cause**: Transaction tidak commit

**Solution**: Check CheckoutController, pastikan `DB::commit()` dipanggil

## Expected Results

### ✅ Success Indicators

1. **Alert muncul** dengan detail pesanan
2. **Cart dikosongkan** (cart counter = 0)
3. **Order masuk database**:
   ```bash
   php artisan tinker
   >>> App\Models\Order::latest()->first()
   ```
4. **Order muncul di admin panel** (status: pending)
5. **Stock produk berkurang**:
   ```bash
   php artisan tinker
   >>> App\Models\Product::find(1)->stock
   ```

### ❌ Failure Indicators

1. Alert error muncul
2. Cart tidak dikosongkan
3. Order tidak masuk database
4. Console error di browser
5. Stock tidak berubah

## Debug Mode

### Enable Debug

Edit `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Restart server:
```bash
php artisan serve
```

### View Logs

Terminal 1 (Server):
```bash
php artisan serve
```

Terminal 2 (Logs):
```bash
tail -f storage/logs/laravel.log
```

### Browser Console

1. Open Developer Tools (F12)
2. Go to Console tab
3. Look for errors (red text)
4. Go to Network tab
5. Filter: XHR
6. Click `/checkout` request
7. View Response tab

## Test Data

### Test Products
- ID 1: Beras Premium 5kg (Rp 75.000)
- ID 2: Minyak Goreng 2L (Rp 32.000)
- ID 5: Susu UHT 1L (Rp 18.000)

### Test Payment Methods
- bca
- mandiri
- bri
- gopay
- ovo
- dana

## Automated Test (Optional)

Create test file: `tests/Feature/CheckoutTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckoutTest extends TestCase
{
    public function test_checkout_creates_order()
    {
        $product = Product::first();
        
        $response = $this->postJson('/checkout', [
            'payment_method' => 'bca',
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->product_price,
                ]
            ]
        ]);

        $response->assertStatus(201);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('orders', [
            'product_id' => $product->id,
            'payment_method' => 'bca',
            'status' => 'pending',
        ]);
    }
}
```

Run test:
```bash
php artisan test --filter CheckoutTest
```

## Checklist

Before testing:
- [ ] Server running
- [ ] Assets built
- [ ] Cache cleared
- [ ] Database seeded

During testing:
- [ ] Can add products to cart
- [ ] Cart counter updates
- [ ] Can view cart
- [ ] Can select payment method
- [ ] Can click checkout button
- [ ] Alert shows success message
- [ ] Cart clears after checkout

After testing:
- [ ] Order in database
- [ ] Order in admin panel
- [ ] Stock decreased
- [ ] Payment method saved
- [ ] Status is "pending"

## Next Steps

After successful checkout:
1. Login to admin panel
2. Go to Orders menu
3. Find the new order
4. Update status to "paid" (after payment verification)
5. Update status to "processing"
6. Update status to "shipped"
7. Update status to "delivered"

## Support

If you encounter issues:
- Check this guide first
- Check Laravel logs
- Check browser console
- Contact: fatahgilang23@gmail.com
