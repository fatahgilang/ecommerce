<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ShopController;

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

    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/{customer}', [CustomerController::class, 'show']);
        Route::put('/{customer}', [CustomerController::class, 'update']);
        Route::delete('/{customer}', [CustomerController::class, 'destroy']);
        Route::get('/{customer}/orders', [CustomerController::class, 'orders']);
        Route::get('/{customer}/reviews', [CustomerController::class, 'reviews']);
    });

    // Categories
    Route::get('/categories', function () {
        $categories = \App\Models\ProductCategory::select('category_name', 'slug', 'icon')
            ->distinct()
            ->active()
            ->ordered()
            ->get();
        return response()->json($categories);
    });

    // Search
    Route::get('/search', function (Request $request) {
        $query = $request->get('q');
        
        $products = \App\Models\Product::where('product_name', 'like', "%{$query}%")
            ->orWhere('product_description', 'like', "%{$query}%")
            ->active()
            ->inStock()
            ->with('shop')
            ->limit(10)
            ->get();

        $shops = \App\Models\Shop::where('shop_name', 'like', "%{$query}%")
            ->active()
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
                ->active()
                ->inStock()
                ->popular()
                ->limit(8)
                ->get(),
            
            'best_sellers' => \App\Models\Product::with('shop')
                ->active()
                ->inStock()
                ->bestSellers()
                ->limit(8)
                ->get(),
            
            'new_arrivals' => \App\Models\Product::with('shop')
                ->active()
                ->inStock()
                ->latest()
                ->limit(8)
                ->get(),
            
            'categories' => \App\Models\ProductCategory::select('category_name', 'slug', 'icon')
                ->distinct()
                ->active()
                ->ordered()
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