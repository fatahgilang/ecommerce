# 🐛 Debug Checkout - Panduan Lengkap

## Problem
Checkout dari browser tidak masuk ke database/admin panel.

## Solution Steps

### Step 1: Clear Everything

```bash
# Clear all caches
php artisan optimize:clear

# Clear logs
> storage/logs/laravel.log

# Rebuild assets
npm run build
```

### Step 2: Start Monitoring

**Terminal 1 - Server:**
```bash
php artisan serve
```

**Terminal 2 - Logs:**
```bash
bash test-checkout-live.sh
```

Atau manual:
```bash
tail -f storage/logs/laravel.log
```

### Step 3: Test Checkout from Browser

1. Open browser: `http://localhost:8000`
2. Open Developer Tools (F12)
3. Go to Console tab
4. Add product to cart
5. Go to cart
6. Select payment method
7. Click "Buat Pesanan"
8. Watch:
   - Browser console for errors
   - Terminal 2 for Laravel logs
   - Alert message

### Step 4: Check Results

**Check if order created:**
```bash
php artisan tinker --execute="
\$latest = App\Models\Order::latest()->first();
if (\$latest) {
    echo 'Latest Order:' . PHP_EOL;
    echo 'ID: ' . \$latest->id . PHP_EOL;
    echo 'Product: ' . \$latest->product->product_name . PHP_EOL;
    echo 'Payment: ' . \$latest->payment_method . PHP_EOL;
    echo 'Created: ' . \$latest->created_at . PHP_EOL;
} else {
    echo 'No orders found!' . PHP_EOL;
}
"
```

**Check admin panel:**
```
URL: http://localhost:8000/admin/orders
Login: admin@example.com / password
```

## Common Issues & Solutions

### Issue 1: CSRF Token Mismatch

**Symptoms:**
- Console error: "CSRF token mismatch"
- Network tab shows 419 error

**Solution:**
```bash
# Hard refresh browser
Ctrl+Shift+R (Windows) or Cmd+Shift+R (Mac)

# Or clear browser cache completely
```

### Issue 2: Network Error

**Symptoms:**
- Console error: "Network Error"
- Request fails immediately

**Solution:**
```bash
# Check if server is running
curl http://localhost:8000

# Restart server
php artisan serve
```

### Issue 3: Validation Error

**Symptoms:**
- Alert shows validation error
- Console shows 422 error

**Solution:**
Check browser console Network tab:
1. Click `/checkout` request
2. View Response tab
3. See which field failed validation

### Issue 4: JavaScript Error

**Symptoms:**
- Button doesn't work
- No request sent
- Console shows JS error

**Solution:**
```bash
# Rebuild assets
npm run build

# Hard refresh browser
Ctrl+Shift+R
```

### Issue 5: Order Created but Not Visible

**Symptoms:**
- Alert shows success
- Order in database
- But not in admin panel

**Solution:**
```bash
# Clear Filament cache
php artisan filament:cache-components

# Clear all caches
php artisan optimize:clear

# Refresh admin panel
```

## Debug Checklist

### Before Testing
- [ ] Server running (`php artisan serve`)
- [ ] Assets built (`npm run build`)
- [ ] Caches cleared (`php artisan optimize:clear`)
- [ ] Logs cleared (`> storage/logs/laravel.log`)
- [ ] Browser cache cleared (Ctrl+Shift+R)

### During Testing
- [ ] Developer Tools open (F12)
- [ ] Console tab visible
- [ ] Network tab visible
- [ ] Logs terminal visible

### After Checkout Click
- [ ] Check browser console for errors
- [ ] Check Network tab for `/checkout` request
- [ ] Check response status (should be 201)
- [ ] Check response body (should have `success: true`)
- [ ] Check Laravel logs for "Checkout" entries
- [ ] Check alert message

### Verification
- [ ] Order in database (tinker command)
- [ ] Order in admin panel
- [ ] Stock decreased
- [ ] Cart cleared
- [ ] Cart counter = 0

## Manual Test via cURL

```bash
# Get a product
PRODUCT_ID=1
PRODUCT_PRICE=75000

# Test checkout
curl -X POST http://localhost:8000/checkout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"payment_method\": \"bca\",
    \"items\": [
      {
        \"product_id\": $PRODUCT_ID,
        \"quantity\": 1,
        \"price\": $PRODUCT_PRICE
      }
    ]
  }"
```

**Expected Response:**
```json
{
  "success": true,
  "message": "Pesanan berhasil dibuat!",
  "data": {
    "customer": { "id": 15 },
    "orders": [...],
    "subtotal": 75000,
    "total": 75000,
    "payment_method": "bca"
  }
}
```

## Log Analysis

### Successful Checkout Logs

```
[timestamp] local.INFO: Checkout request received {"data":{"payment_method":"bca","items":[...]}}
[timestamp] local.INFO: Validation passed {"validated":{...}}
[timestamp] local.INFO: Customer created {"customer_id":15}
[timestamp] local.INFO: Processing item {"product_id":1,"product_name":"Beras Premium 5kg",...}
[timestamp] local.INFO: Order created {"order_id":153}
[timestamp] local.INFO: Checkout completed successfully {"customer_id":15,"total_orders":1,...}
```

### Failed Checkout Logs

```
[timestamp] local.ERROR: Checkout failed {"error":"...","trace":"..."}
```

## Browser Console Debug

Add this to browser console to debug:

```javascript
// Check if axios is configured
console.log('Axios headers:', window.axios.defaults.headers.common);

// Check CSRF token
const token = document.head.querySelector('meta[name="csrf-token"]');
console.log('CSRF Token:', token ? token.content : 'NOT FOUND');

// Test checkout manually
const testCheckout = async () => {
  try {
    const response = await window.axios.post('/checkout', {
      payment_method: 'bca',
      items: [
        {
          product_id: 1,
          quantity: 1,
          price: 75000
        }
      ]
    });
    console.log('Success:', response.data);
  } catch (error) {
    console.error('Error:', error.response?.data || error.message);
  }
};

// Run test
testCheckout();
```

## Database Direct Check

```sql
-- Check latest orders
SELECT 
    o.id,
    c.name as customer,
    p.product_name,
    o.product_quantity,
    o.total_price,
    o.payment_method,
    o.status,
    o.created_at
FROM orders o
JOIN customers c ON o.customer_id = c.id
JOIN products p ON o.product_id = p.id
ORDER BY o.created_at DESC
LIMIT 5;

-- Count orders by status
SELECT status, COUNT(*) as total
FROM orders
GROUP BY status;

-- Check guest customers
SELECT * FROM customers 
WHERE email LIKE 'guest_%' 
ORDER BY created_at DESC 
LIMIT 5;
```

## Emergency Fix

If nothing works, try this:

```bash
# 1. Stop server
# Press Ctrl+C in server terminal

# 2. Clear everything
php artisan optimize:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 3. Rebuild
composer dump-autoload
npm run build

# 4. Restart server
php artisan serve

# 5. Test again
```

## Contact Support

If still not working:
1. Copy error from browser console
2. Copy error from Laravel logs
3. Copy response from Network tab
4. Email to: fatahgilang23@gmail.com
5. WhatsApp: 082333058317

Include:
- Browser used (Chrome/Firefox/Safari)
- Error messages
- Screenshots
- Steps to reproduce

## Success Indicators

✅ **Checkout is working if:**
1. No errors in browser console
2. Alert shows success message with order details
3. Cart clears (counter = 0)
4. Order appears in database (tinker check)
5. Order appears in admin panel
6. Stock decreases
7. Laravel logs show "Checkout completed successfully"

## Next Steps After Fix

1. Test with different products
2. Test with multiple items
3. Test with different payment methods
4. Test stock validation (try to order more than available)
5. Test with out of stock product
6. Document any issues found
