<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CashRegisterController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\ReportController;
use App\Services\ReportService;

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
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/statistics', [OrderController::class, 'statistics']);
        Route::get('/{order}', [OrderController::class, 'show']);
    });

    // Categories
    Route::get('/categories', function () {
        $categories = \App\Models\ProductCategory::select('category_name')
            ->distinct()
            ->orderBy('category_name')
            ->get()
            ->pluck('category_name');
        return response()->json($categories);
    });

    // Main Categories (for navigation)
    Route::get('/categories/main', function () {
        $categories = \App\Models\NavigationCategory::active()
            ->ordered()
            ->get(['name', 'slug', 'icon', 'color']);
            
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
        Route::get('/sales', function(Request $request) {
            $service = new ReportService();
            $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->format('Y-m-d'));
            $groupBy = $request->get('group_by', 'day');
            
            return response()->json($service->salesReport($startDate, $endDate, $groupBy));
        });
        
        Route::get('/products', function(Request $request) {
            $service = new ReportService();
            $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->format('Y-m-d'));
            
            return response()->json($service->productReport($startDate, $endDate));
        });
        
        Route::get('/cashiers', function(Request $request) {
            $service = new ReportService();
            $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->format('Y-m-d'));
            
            return response()->json($service->cashierReport($startDate, $endDate));
        });
        
        Route::get('/cashflow', function(Request $request) {
            $service = new ReportService();
            $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->format('Y-m-d'));
            
            return response()->json($service->cashFlowReport($startDate, $endDate));
        });
        
        Route::get('/profitloss', function(Request $request) {
            $service = new ReportService();
            $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->get('end_date', now()->format('Y-m-d'));
            
            return response()->json($service->profitLossReport($startDate, $endDate));
        });
        
        Route::get('/inventory', function() {
            $service = new ReportService();
            return response()->json($service->inventoryReport());
        });
        
        Route::get('/dashboard', [ReportController::class, 'dashboard']);
    });

    // Stock Predictions & AI Insights
    Route::prefix('predictions')->group(function () {
        Route::get('/', [\App\Http\Controllers\Api\StockPredictionController::class, 'index']);
        Route::get('/insights', [\App\Http\Controllers\Api\StockPredictionController::class, 'insights']);
        Route::get('/critical', [\App\Http\Controllers\Api\StockPredictionController::class, 'critical']);
        Route::get('/{productId}', [\App\Http\Controllers\Api\StockPredictionController::class, 'show']);
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
                    'message' => 'Kode diskon tidak ditemukan atau sudah tidak berlaku'
                ], 404);
            }
            
            if (!$discount->isValid($request->amount)) {
                $reasons = [];
                
                if (!$discount->is_active) {
                    $reasons[] = 'Diskon tidak aktif';
                }
                
                if (now()->lt($discount->start_date)) {
                    $reasons[] = 'Diskon belum berlaku (mulai ' . $discount->start_date->format('d/m/Y') . ')';
                }
                
                if (now()->gt($discount->end_date)) {
                    $reasons[] = 'Diskon sudah berakhir (' . $discount->end_date->format('d/m/Y') . ')';
                }
                
                if ($discount->usage_limit && $discount->used_count >= $discount->usage_limit) {
                    $reasons[] = 'Batas penggunaan diskon sudah tercapai';
                }
                
                if ($request->amount < $discount->minimum_amount) {
                    $reasons[] = 'Minimum pembelian Rp ' . number_format($discount->minimum_amount, 0, ',', '.');
                }
                
                $message = count($reasons) > 0 ? implode(', ', $reasons) : 'Diskon tidak dapat digunakan';
                
                return response()->json([
                    'valid' => false,
                    'message' => $message
                ], 422);
            }
            
            return response()->json([
                'valid' => true,
                'discount' => [
                    'id' => $discount->id,
                    'name' => $discount->name,
                    'type' => $discount->type,
                    'value' => $discount->value,
                    'maximum_discount' => $discount->maximum_discount,
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
            ->limit(10)
            ->get();

        return response()->json([
            'products' => $products,
        ]);
    });

    // Home data (featured products, best sellers, etc.)
    Route::get('/home', function () {
        return response()->json([
            'featured_products' => \App\Models\Product::limit(8)->get(),
            
            'best_sellers' => \App\Models\Product::limit(8)->get(),
            
            'new_arrivals' => \App\Models\Product::latest()->limit(8)->get(),
            
            'categories' => \App\Models\NavigationCategory::active()
                ->ordered()
                ->pluck('name'),
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
