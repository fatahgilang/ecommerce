<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockPredictionService
{
    /**
     * Analisis penjualan dan prediksi stok
     */
    public function analyzeAndPredict()
    {
        $products = Product::where('stock', '>', 0)->get();
        $predictions = [];

        foreach ($products as $product) {
            $prediction = $this->predictProductStock($product);
            if ($prediction) {
                $predictions[] = $prediction;
            }
        }

        // Sort by urgency (days until out of stock)
        usort($predictions, function($a, $b) {
            return $a['days_until_empty'] <=> $b['days_until_empty'];
        });

        return $predictions;
    }

    /**
     * Prediksi stok untuk satu produk
     */
    public function predictProductStock(Product $product)
    {
        // Get sales data for last 30 days
        $salesData = $this->getSalesData($product->id, 30);
        
        if ($salesData['total_sold'] == 0) {
            return null; // No sales data
        }

        $dailyAverage = $salesData['daily_average'];
        $currentStock = $product->stock;
        
        // Calculate days until stock runs out
        $daysUntilEmpty = $dailyAverage > 0 ? ceil($currentStock / $dailyAverage) : 999;
        
        // Calculate recommended reorder quantity
        $reorderQuantity = $this->calculateReorderQuantity($dailyAverage, $salesData);
        
        // Determine urgency level
        $urgency = $this->determineUrgency($daysUntilEmpty);
        
        // Generate recommendation
        $recommendation = $this->generateRecommendation(
            $product,
            $daysUntilEmpty,
            $reorderQuantity,
            $urgency,
            $salesData
        );

        return [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'current_stock' => $currentStock,
            'daily_average' => round($dailyAverage, 2),
            'days_until_empty' => $daysUntilEmpty,
            'reorder_quantity' => $reorderQuantity,
            'urgency' => $urgency,
            'recommendation' => $recommendation,
            'sales_trend' => $salesData['trend'],
            'last_30_days_sold' => $salesData['total_sold'],
            'last_7_days_sold' => $salesData['last_7_days'],
        ];
    }

    /**
     * Get sales data for a product
     */
    private function getSalesData($productId, $days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        // Get orders for this product
        $orders = Order::where('product_id', $productId)
            ->where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalSold = $orders->sum('product_quantity');
        $dailyAverage = $totalSold / $days;

        // Get last 7 days for trend analysis
        $last7Days = Order::where('product_id', $productId)
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->where('status', '!=', 'cancelled')
            ->sum('product_quantity');

        // Calculate trend
        $last7DaysAvg = $last7Days / 7;
        $trend = $this->calculateTrend($dailyAverage, $last7DaysAvg);

        return [
            'total_sold' => $totalSold,
            'daily_average' => $dailyAverage,
            'last_7_days' => $last7Days,
            'trend' => $trend,
        ];
    }

    /**
     * Calculate sales trend
     */
    private function calculateTrend($overallAvg, $recentAvg)
    {
        if ($overallAvg == 0) return 'stable';
        
        $change = (($recentAvg - $overallAvg) / $overallAvg) * 100;
        
        if ($change > 20) return 'increasing';
        if ($change < -20) return 'decreasing';
        return 'stable';
    }

    /**
     * Calculate recommended reorder quantity
     */
    private function calculateReorderQuantity($dailyAverage, $salesData)
    {
        // Base: 30 days supply
        $baseQuantity = ceil($dailyAverage * 30);
        
        // Adjust based on trend
        if ($salesData['trend'] === 'increasing') {
            $baseQuantity = ceil($baseQuantity * 1.3); // +30% for increasing trend
        } elseif ($salesData['trend'] === 'decreasing') {
            $baseQuantity = ceil($baseQuantity * 0.8); // -20% for decreasing trend
        }
        
        return max(1, $baseQuantity);
    }

    /**
     * Determine urgency level
     */
    private function determineUrgency($daysUntilEmpty)
    {
        if ($daysUntilEmpty <= 3) return 'critical';
        if ($daysUntilEmpty <= 7) return 'high';
        if ($daysUntilEmpty <= 14) return 'medium';
        return 'low';
    }

    /**
     * Generate AI-like recommendation
     */
    private function generateRecommendation($product, $daysUntilEmpty, $reorderQuantity, $urgency, $salesData)
    {
        $recommendations = [];

        // Stock warning
        if ($urgency === 'critical') {
            $recommendations[] = "⚠️ URGENT: Stok {$product->product_name} akan habis dalam {$daysUntilEmpty} hari!";
            $recommendations[] = "Segera order {$reorderQuantity} unit ke supplier.";
        } elseif ($urgency === 'high') {
            $recommendations[] = "⚡ Perhatian: Stok {$product->product_name} menipis ({$daysUntilEmpty} hari lagi).";
            $recommendations[] = "Disarankan order {$reorderQuantity} unit minggu ini.";
        } elseif ($urgency === 'medium') {
            $recommendations[] = "📊 Stok {$product->product_name} cukup untuk {$daysUntilEmpty} hari.";
            $recommendations[] = "Rencanakan order {$reorderQuantity} unit dalam 1-2 minggu.";
        } else {
            $recommendations[] = "✅ Stok {$product->product_name} aman untuk {$daysUntilEmpty} hari.";
        }

        // Trend analysis
        if ($salesData['trend'] === 'increasing') {
            $recommendations[] = "📈 Penjualan meningkat! Pertimbangkan stok lebih banyak.";
        } elseif ($salesData['trend'] === 'decreasing') {
            $recommendations[] = "📉 Penjualan menurun. Order dengan hati-hati.";
        }

        // Sales insight
        $avgPerDay = round($salesData['daily_average'], 1);
        $recommendations[] = "💡 Rata-rata terjual {$avgPerDay} unit/hari dalam 30 hari terakhir.";

        return implode(' ', $recommendations);
    }

    /**
     * Get dashboard insights
     */
    public function getDashboardInsights()
    {
        $insights = [];

        // Critical stock items
        $criticalItems = $this->getCriticalStockItems();
        if (count($criticalItems) > 0) {
            $insights[] = [
                'type' => 'critical',
                'icon' => '🚨',
                'title' => 'Stok Kritis',
                'message' => count($criticalItems) . ' produk akan habis dalam 3 hari!',
                'action' => 'Lihat Detail',
                'data' => $criticalItems,
            ];
        }

        // Best selling products
        $bestSellers = $this->getBestSellingProducts();
        if (count($bestSellers) > 0) {
            $insights[] = [
                'type' => 'success',
                'icon' => '🏆',
                'title' => 'Produk Terlaris',
                'message' => 'Top 5 produk dengan penjualan tertinggi bulan ini',
                'action' => 'Lihat Detail',
                'data' => $bestSellers,
            ];
        }

        // Slow moving products
        $slowMoving = $this->getSlowMovingProducts();
        if (count($slowMoving) > 0) {
            $insights[] = [
                'type' => 'warning',
                'icon' => '🐌',
                'title' => 'Produk Slow Moving',
                'message' => count($slowMoving) . ' produk jarang terjual',
                'action' => 'Lihat Detail',
                'data' => $slowMoving,
            ];
        }

        // Revenue trend
        $revenueTrend = $this->getRevenueTrend();
        $insights[] = [
            'type' => 'info',
            'icon' => '💰',
            'title' => 'Trend Pendapatan',
            'message' => $revenueTrend['message'],
            'action' => 'Lihat Grafik',
            'data' => $revenueTrend,
        ];

        return $insights;
    }

    /**
     * Get critical stock items
     */
    private function getCriticalStockItems()
    {
        $predictions = $this->analyzeAndPredict();
        return array_filter($predictions, function($p) {
            return $p['urgency'] === 'critical';
        });
    }

    /**
     * Get best selling products
     */
    private function getBestSellingProducts($limit = 5)
    {
        $startDate = Carbon::now()->subDays(30);
        
        return Order::select('product_id', DB::raw('SUM(product_quantity) as total_sold'))
            ->with('product')
            ->where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($order) {
                return [
                    'product_name' => $order->product->product_name,
                    'total_sold' => $order->total_sold,
                    'revenue' => $order->total_sold * $order->product->product_price,
                ];
            })
            ->toArray();
    }

    /**
     * Get slow moving products
     */
    private function getSlowMovingProducts($limit = 5)
    {
        $startDate = Carbon::now()->subDays(30);
        
        $soldProducts = Order::select('product_id', DB::raw('SUM(product_quantity) as total_sold'))
            ->where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->groupBy('product_id')
            ->pluck('total_sold', 'product_id');

        return Product::where('stock', '>', 0)
            ->get()
            ->map(function($product) use ($soldProducts) {
                $sold = $soldProducts[$product->id] ?? 0;
                return [
                    'product_name' => $product->product_name,
                    'stock' => $product->stock,
                    'sold_30_days' => $sold,
                    'stock_value' => $product->stock * $product->product_price,
                ];
            })
            ->sortBy('sold_30_days')
            ->take($limit)
            ->values()
            ->toArray();
    }

    /**
     * Get revenue trend
     */
    private function getRevenueTrend()
    {
        $thisMonth = Transaction::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        $lastMonth = Transaction::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_amount');

        $change = $lastMonth > 0 ? (($thisMonth - $lastMonth) / $lastMonth) * 100 : 0;

        $message = '';
        if ($change > 10) {
            $message = "Pendapatan meningkat " . round($change, 1) . "% dari bulan lalu! 📈";
        } elseif ($change < -10) {
            $message = "Pendapatan menurun " . abs(round($change, 1)) . "% dari bulan lalu. 📉";
        } else {
            $message = "Pendapatan stabil dibanding bulan lalu.";
        }

        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'change_percent' => round($change, 1),
            'message' => $message,
        ];
    }
}
