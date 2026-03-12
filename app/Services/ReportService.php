<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Laporan Penjualan
     */
    public function salesReport($startDate, $endDate, $groupBy = 'day')
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get transactions
        $transactions = Transaction::whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        // Group by period
        $grouped = $this->groupByPeriod($transactions, $groupBy);

        // Calculate totals
        $totalRevenue = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // Get payment method breakdown
        $paymentMethods = $transactions->groupBy('payment_method')
            ->map(function($group) {
                return [
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount'),
                ];
            });

        return [
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'days' => $start->diffInDays($end) + 1,
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_transactions' => $totalTransactions,
                'average_transaction' => round($averageTransaction, 2),
                'daily_average' => round($totalRevenue / ($start->diffInDays($end) + 1), 2),
            ],
            'payment_methods' => $paymentMethods,
            'chart_data' => $grouped,
        ];
    }

    /**
     * Laporan Produk
     */
    public function productReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get product sales
        $productSales = Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->select('product_id', 
                DB::raw('SUM(product_quantity) as total_sold'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(*) as order_count'))
            ->groupBy('product_id')
            ->with('product')
            ->get()
            ->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->product_name,
                    'category' => $item->product->categories->first()->category_name ?? 'Uncategorized',
                    'total_sold' => $item->total_sold,
                    'total_revenue' => $item->total_revenue,
                    'order_count' => $item->order_count,
                    'current_stock' => $item->product->stock,
                    'stock_value' => $item->product->stock * $item->product->product_price,
                ];
            })
            ->sortByDesc('total_revenue')
            ->values();

        // Top 10 products
        $topProducts = $productSales->take(10);

        // Low stock products
        $lowStock = Product::where('stock', '<=', 10)
            ->where('stock', '>', 0)
            ->get()
            ->map(function($product) {
                return [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'stock' => $product->stock,
                    'price' => $product->product_price,
                    'stock_value' => $product->stock * $product->product_price,
                ];
            });

        // Out of stock products
        $outOfStock = Product::where('stock', '=', 0)->count();

        return [
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
            'summary' => [
                'total_products_sold' => $productSales->sum('total_sold'),
                'total_revenue' => $productSales->sum('total_revenue'),
                'unique_products' => $productSales->count(),
                'out_of_stock_count' => $outOfStock,
            ],
            'top_products' => $topProducts,
            'all_products' => $productSales,
            'low_stock' => $lowStock,
        ];
    }

    /**
     * Laporan Kasir
     */
    public function cashierReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get cashier performance
        $cashiers = Transaction::whereBetween('created_at', [$start, $end])
            ->select('cashier_id',
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_amount) as total_revenue'),
                DB::raw('AVG(total_amount) as average_transaction'))
            ->groupBy('cashier_id')
            ->with('cashier')
            ->get()
            ->map(function($item) use ($start, $end) {
                $cashier = $item->cashier;
                
                // Get daily breakdown
                $dailyTransactions = Transaction::where('cashier_id', $item->cashier_id)
                    ->whereBetween('created_at', [$start, $end])
                    ->select(DB::raw('DATE(created_at) as date'), 
                        DB::raw('COUNT(*) as count'),
                        DB::raw('SUM(total_amount) as revenue'))
                    ->groupBy('date')
                    ->get();

                return [
                    'cashier_id' => $item->cashier_id,
                    'cashier_name' => $cashier->name,
                    'transaction_count' => $item->transaction_count,
                    'total_revenue' => $item->total_revenue,
                    'average_transaction' => round($item->average_transaction, 2),
                    'daily_breakdown' => $dailyTransactions,
                ];
            })
            ->sortByDesc('total_revenue')
            ->values();

        return [
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
            'summary' => [
                'total_cashiers' => $cashiers->count(),
                'total_transactions' => $cashiers->sum('transaction_count'),
                'total_revenue' => $cashiers->sum('total_revenue'),
            ],
            'cashiers' => $cashiers,
        ];
    }

    /**
     * Laporan Kas
     */
    public function cashFlowReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get cash transactions
        $transactions = Transaction::whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get();

        // Group by payment method
        $byPaymentMethod = $transactions->groupBy('payment_method')
            ->map(function($group, $method) {
                return [
                    'method' => $method,
                    'count' => $group->count(),
                    'total' => $group->sum('total_amount'),
                    'percentage' => 0, // Will calculate later
                ];
            });

        $totalRevenue = $transactions->sum('total_amount');
        
        // Calculate percentages
        $byPaymentMethod = $byPaymentMethod->map(function($item) use ($totalRevenue) {
            $item['percentage'] = $totalRevenue > 0 ? round(($item['total'] / $totalRevenue) * 100, 2) : 0;
            return $item;
        });

        // Daily cash flow
        $dailyCashFlow = $transactions->groupBy(function($transaction) {
            return $transaction->created_at->format('Y-m-d');
        })->map(function($group, $date) {
            return [
                'date' => $date,
                'transactions' => $group->count(),
                'revenue' => $group->sum('total_amount'),
                'cash' => $group->where('payment_method', 'cash')->sum('total_amount'),
                'non_cash' => $group->whereNotIn('payment_method', ['cash'])->sum('total_amount'),
            ];
        })->values();

        return [
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'cash_revenue' => $transactions->where('payment_method', 'cash')->sum('total_amount'),
                'non_cash_revenue' => $transactions->whereNotIn('payment_method', ['cash'])->sum('total_amount'),
                'total_transactions' => $transactions->count(),
            ],
            'by_payment_method' => $byPaymentMethod->values(),
            'daily_cash_flow' => $dailyCashFlow,
        ];
    }

    /**
     * Laporan Laba Rugi (Simplified)
     */
    public function profitLossReport($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Revenue from transactions
        $revenue = Transaction::whereBetween('created_at', [$start, $end])
            ->sum('total_amount');

        // Get discount given
        $discounts = Order::whereBetween('created_at', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('discount_amount');

        // Gross revenue (before discount)
        $grossRevenue = $revenue + $discounts;

        // Calculate COGS (simplified: assume 60% of selling price)
        $cogs = $revenue * 0.6;

        // Gross profit
        $grossProfit = $revenue - $cogs;
        $grossProfitMargin = $revenue > 0 ? ($grossProfit / $revenue) * 100 : 0;

        // Operating expenses (simplified)
        $operatingExpenses = 0; // Can be extended to include actual expenses

        // Net profit
        $netProfit = $grossProfit - $operatingExpenses;
        $netProfitMargin = $revenue > 0 ? ($netProfit / $revenue) * 100 : 0;

        return [
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
            'revenue' => [
                'gross_revenue' => $grossRevenue,
                'discounts' => $discounts,
                'net_revenue' => $revenue,
            ],
            'costs' => [
                'cogs' => $cogs,
                'operating_expenses' => $operatingExpenses,
                'total_costs' => $cogs + $operatingExpenses,
            ],
            'profit' => [
                'gross_profit' => $grossProfit,
                'gross_profit_margin' => round($grossProfitMargin, 2),
                'net_profit' => $netProfit,
                'net_profit_margin' => round($netProfitMargin, 2),
            ],
        ];
    }

    /**
     * Laporan Inventory
     */
    public function inventoryReport()
    {
        $products = Product::with('categories')->get();

        $totalProducts = $products->count();
        $totalStockValue = $products->sum(function($product) {
            return $product->stock * $product->product_price;
        });
        $totalStockQty = $products->sum('stock');
        $outOfStock = $products->where('stock', 0)->count();
        $lowStock = $products->where('stock', '>', 0)->where('stock', '<=', 10)->count();

        // Group by category
        $byCategory = $products->groupBy(function($product) {
            return $product->categories->first()->category_name ?? 'Uncategorized';
        })->map(function($group, $category) {
            $stockValue = $group->sum(function($product) {
                return $product->stock * $product->product_price;
            });
            
            return [
                'category' => $category,
                'product_count' => $group->count(),
                'total_stock' => $group->sum('stock'),
                'stock_value' => $stockValue,
            ];
        })->sortByDesc('stock_value')->values();

        // Products detail
        $productsDetail = $products->map(function($product) {
            return [
                'product_id' => $product->id,
                'product_name' => $product->product_name,
                'category' => $product->categories->first()->category_name ?? 'Uncategorized',
                'stock' => $product->stock,
                'price' => $product->product_price,
                'stock_value' => $product->stock * $product->product_price,
                'status' => $product->stock == 0 ? 'out_of_stock' : ($product->stock <= 10 ? 'low_stock' : 'in_stock'),
            ];
        })->sortByDesc('stock_value')->values();

        return [
            'summary' => [
                'total_products' => $totalProducts,
                'total_stock_qty' => $totalStockQty,
                'total_stock_value' => $totalStockValue,
                'out_of_stock' => $outOfStock,
                'low_stock' => $lowStock,
                'in_stock' => $totalProducts - $outOfStock - $lowStock,
            ],
            'by_category' => $byCategory,
            'products' => $productsDetail,
        ];
    }

    /**
     * Group data by period
     */
    private function groupByPeriod($transactions, $groupBy)
    {
        return $transactions->groupBy(function($transaction) use ($groupBy) {
            switch($groupBy) {
                case 'hour':
                    return $transaction->created_at->format('Y-m-d H:00');
                case 'day':
                    return $transaction->created_at->format('Y-m-d');
                case 'week':
                    return $transaction->created_at->startOfWeek()->format('Y-m-d');
                case 'month':
                    return $transaction->created_at->format('Y-m');
                case 'year':
                    return $transaction->created_at->format('Y');
                default:
                    return $transaction->created_at->format('Y-m-d');
            }
        })->map(function($group, $period) {
            return [
                'period' => $period,
                'transactions' => $group->count(),
                'revenue' => $group->sum('total_amount'),
            ];
        })->values();
    }

    /**
     * Export report to CSV
     */
    public function exportToCSV($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 (helps Excel recognize UTF-8)
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Determine max column count
            $maxColumns = 0;
            foreach ($data as $row) {
                $maxColumns = max($maxColumns, count($row));
            }
            
            // Write data
            foreach ($data as $row) {
                // Ensure all rows have the same number of columns
                while (count($row) < $maxColumns) {
                    $row[] = '';
                }
                
                // Convert all values to string and handle arrays/objects
                $cleanRow = array_map(function($value) {
                    if (is_array($value) || is_object($value)) {
                        return json_encode($value);
                    }
                    return (string) $value;
                }, $row);
                
                fputcsv($file, $cleanRow);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
