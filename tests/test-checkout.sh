#!/bin/bash

# Test Checkout Script
echo "Testing Checkout Functionality..."
echo "=================================="
echo ""

# Get CSRF token
echo "1. Getting CSRF token..."
CSRF_TOKEN=$(php artisan tinker --execute="echo csrf_token();")
echo "CSRF Token: $CSRF_TOKEN"
echo ""

# Test checkout endpoint
echo "2. Testing checkout endpoint..."
curl -X POST http://localhost:8000/checkout \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: $CSRF_TOKEN" \
  -H "Accept: application/json" \
  -d '{
    "payment_method": "bca",
    "items": [
      {
        "product_id": 1,
        "quantity": 1,
        "price": 75000
      }
    ]
  }' | jq '.'

echo ""
echo "3. Checking latest order..."
php artisan tinker --execute="
\$order = App\Models\Order::latest()->first();
if (\$order) {
    echo 'Latest Order:' . PHP_EOL;
    echo 'ID: ' . \$order->id . PHP_EOL;
    echo 'Product: ' . \$order->product->product_name . PHP_EOL;
    echo 'Quantity: ' . \$order->product_quantity . PHP_EOL;
    echo 'Total: Rp ' . number_format(\$order->total_price, 0, ',', '.') . PHP_EOL;
    echo 'Payment: ' . \$order->payment_method . PHP_EOL;
    echo 'Status: ' . \$order->status . PHP_EOL;
} else {
    echo 'No orders found!' . PHP_EOL;
}
"

echo ""
echo "Test completed!"
