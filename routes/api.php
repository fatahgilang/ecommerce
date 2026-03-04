<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\CashRegisterController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::prefix('v1')->group(function () {
    
    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/featured', [ProductController::class, 'featured']);
        Route::get('/best-sellers', [ProductController::class, 'bestSellers']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::get('/{product}/reviews', [ProductController::class, 'reviews']);
        Route::post('/{product}/reviews', [ProductController::class, 'addReview']);
    });

    // Shops
    Route::prefix('shops')->group(function () {
        Route::get('/', [ShopController::class, 'index']);
        Route::get('/{shop}', [ShopController::class, 'show']);
        Route::get('/{shop}/products', [ShopController::class, 'products']);
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/statistics', [OrderController::class, 'statistics']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::post('/{order}/cancel', [OrderController::class, 'cancel']);
    });

    // Categories
    Route::get('/categories', function () {
        $categories = \App\Models\ProductCategory::select('category_name')
            ->distinct()
            ->get();
        return response()->json($categories);
    });

    // Cash Registers
    Route::prefix('cash-registers')->group(function () {
        Route::get('/current', [CashRegisterController::class, 'current']);
        Route::post('/open', [CashRegisterController::class, 'open']);
        Route::post('/{cashRegister}/close', [CashRegisterController::class, 'close']);
        Route::get('/history', [CashRegisterController::class, 'history']);
        Route::get('/{cashRegister}/summary', [CashRegisterController::class, 'summary']);
    });

    // Transactions (POS)
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/{transaction}', [TransactionController::class, 'show']);
        Route::post('/{transaction}/cancel', [TransactionController::class, 'cancel']);
        Route::post('/{transaction}/refund', [TransactionController::class, 'refund']);
        Route::get('/{transaction}/receipt', [TransactionController::class, 'receipt']);
    });

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales']);
        Route::get('/products', [ReportController::class, 'products']);
        Route::get('/cashiers', [ReportController::class, 'cashiers']);
        Route::get('/dashboard', [ReportController::class, 'dashboard']);
    });

    // Discounts
    Route::prefix('discounts')->group(function () {
        Route::get('/', function (Request $request) {
            $discounts = \App\Models\Discount::active();
            
            if ($request->has('code')) {
                $discounts->where('code', $request->code);
            }
            
            return response()->json($discounts->get());
        });
        
        Route::post('/validate', function (Request $request) {
            $request->validate([
                'code' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);
            
            $discount = \App\Models\Discount::active()->byCode($request->code)->first();
            
            if (!$discount) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Discount code not found'
                ], 404);
            }
            
            if (!$discount->isValid($request->amount)) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Discount code is not valid for this amount'
                ], 422);
            }
            
            return response()->json([
                'valid' => true,
                'discount' => [
                    'id' => $discount->id,
                    'name' => $discount->name,
                    'type' => $discount->type,
                    'value' => $discount->value,
                    'discount_amount' => $discount->calculateDiscount($request->amount),
                ]
            ]);
        });
    });

    // Search
    Route::get('/search', function (Request $request) {
        $query = $request->get('q');
        
        $products = \App\Models\Product::where('product_name', 'like', "%{$query}%")
            ->orWhere('product_description', 'like', "%{$query}%")
            ->with('shop')
            ->limit(10)
            ->get();

        $shops = \App\Models\Shop::where('shop_name', 'like', "%{$query}%")
            ->limit(5)
            ->get();

        return response()->json([
            'products' => $products,
            'shops' => $shops,
        ]);
    });

    // Home data (featured products, best sellers, etc.)
    Route::get('/home', function () {
        return response()->json([
            'featured_products' => \App\Models\Product::with('shop')
                ->limit(8)
                ->get(),
            
            'best_sellers' => \App\Models\Product::with('shop')
                ->limit(8)
                ->get(),
            
            'new_arrivals' => \App\Models\Product::with('shop')
                ->latest()
                ->limit(8)
                ->get(),
            
            'categories' => \App\Models\ProductCategory::select('category_name')
                ->distinct()
                ->limit(8)
                ->get(),
        ]);
    });
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toDateTimeString(),
    ]);
});
