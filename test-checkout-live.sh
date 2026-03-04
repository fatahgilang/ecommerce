#!/bin/bash

echo "=========================================="
echo "  Live Checkout Test"
echo "=========================================="
echo ""
echo "Instructions:"
echo "1. Keep this terminal open"
echo "2. Open browser: http://localhost:8000"
echo "3. Add products to cart"
echo "4. Go to cart and checkout"
echo "5. Watch logs below..."
echo ""
echo "=========================================="
echo "  Watching Laravel Logs..."
echo "=========================================="
echo ""

# Clear old logs
> storage/logs/laravel.log

# Watch logs
tail -f storage/logs/laravel.log | grep --line-buffered -E "(Checkout|Order|Customer|ERROR)"
