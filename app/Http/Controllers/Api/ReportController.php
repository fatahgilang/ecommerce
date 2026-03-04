<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Product;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Get sales report
     */
    public function sales(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());
        $cashRegisterId = $request->get('cash_register_id');

        $query = Transaction::where('status', 'completed')
            ->whereDate('created_at', '>=', $dateFrom)
            ->whereDate('created_at', '<=', $dateTo);

        if ($cashRegisterId) {
            $query->where('cash_register_id', $cashRegisterId);
        }

        // Daily sales
        $dailySales = $query->clone()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('AVG(total_amount) as average_transaction')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // Payment method breakdown
        $paymentMethods = $query->clone()
            ->select('payment_method', DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Summary
        $summary = [
            'total_transactions' => $query->clone()->count(),
            'total_sales' => $query->clone()->sum('total_amount'),
            'average_transaction' => $query->clone()->avg('total_amount'),
            'total_tax' => $query->clone()->sum('tax_amount'),
            'total_discount' => $query->clone()->sum('discount_amount'),
        ];

        return response()->json([
            'period' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'summary' => $summary,
            'daily_sales' => $dailySales,
            'payment_methods' => $paymentMethods,
        ]);
    }

    /**
     * Get product sales report
     */
    public function products(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());
        $limit = $request->get('limit', 20);

        $productSales = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'completed')
            ->whereDate('transactions.created_at', '>=', $dateFrom)
            ->whereDate('transactions.created_at', '<=', $dateTo)
            ->select(
                'products.id',
                'products.product_name',
                'products.product_price',
                'products.stock',
                DB::raw('SUM(transaction_items.quantity) as total_sold'),
                DB::raw('SUM(transaction_items.total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT transactions.id) as transaction_count')
            )
            ->groupBy('products.id', 'products.product_name', 'products.product_price', 'products.stock')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->select('id', 'product_name', 'stock', 'product_price')
            ->orderBy('stock')
            ->limit(10)
            ->get();

        return response()->json([
            'period' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'best_selling_products' => $productSales,
            'low_stock_products' => $lowStockProducts,
        ]);
    }

    /**
     * Get cashier performance report
     */
    public function cashiers(Request $request): JsonResponse
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->get('date_to', now()->toDateString());

        $cashierStats = DB::table('users')
            ->join('transactions', 'users.id', '=', 'transactions.user_id')
            ->where('transactions.status', 'completed')
            ->whereDate('transactions.created_at', '>=', $dateFrom)
            ->whereDate('transactions.created_at', '<=', $dateTo)
            ->select(
                'users.id',
                'users.name',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as average_transaction'),
                DB::raw('MIN(transactions.created_at) as first_transaction'),
                DB::raw('MAX(transactions.created_at) as last_transaction')
            )
            ->groupBy('users.id', 'users.name')
            ->orderBy('total_sales', 'desc')
            ->get();

        // Cash register sessions
        $registerSessions = CashRegister::with('user')
            ->whereDate('opened_at', '>=', $dateFrom)
            ->whereDate('opened_at', '<=', $dateTo)
            ->select(
                'id', 'register_name', 'user_id', 'opening_balance', 
                'closing_balance', 'total_sales', 'status', 
                'opened_at', 'closed_at'
            )
            ->orderBy('opened_at', 'desc')
            ->get()
            ->map(function ($register) {
                return [
                    'id' => $register->id,
                    'register_name' => $register->register_name,
                    'cashier' => $register->user->name,
                    'opening_balance' => $register->opening_balance,
                    'closing_balance' => $register->closing_balance,
                    'total_sales' => $register->total_sales,
                    'status' => $register->status,
                    'opened_at' => $register->opened_at->format('d M Y H:i'),
                    'closed_at' => $register->closed_at?->format('d M Y H:i'),
                    'duration' => $register->closed_at ? 
                        $register->opened_at->diffInHours($register->closed_at) . ' hours' : 
                        'Still open',
                ];
            });

        return response()->json([
            'period' => [
                'from' => $dateFrom,
                'to' => $dateTo,
            ],
            'cashier_performance' => $cashierStats,
            'register_sessions' => $registerSessions,
        ]);
    }

    /**
     * Get dashboard summary
     */
    public function dashboard(Request $request): JsonResponse
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();
        $thisMonth = now()->startOfMonth()->toDateString();
        $lastMonth = now()->subMonth()->startOfMonth()->toDateString();
        $lastMonthEnd = now()->subMonth()->endOfMonth()->toDateString();

        // Today's stats
        $todayStats = [
            'sales' => Transaction::where('status', 'completed')
                ->whereDate('created_at', $today)
                ->sum('total_amount'),
            'transactions' => Transaction::where('status', 'completed')
                ->whereDate('created_at', $today)
                ->count(),
        ];

        // Yesterday's stats for comparison
        $yesterdayStats = [
            'sales' => Transaction::where('status', 'completed')
                ->whereDate('created_at', $yesterday)
                ->sum('total_amount'),
            'transactions' => Transaction::where('status', 'completed')
                ->whereDate('created_at', $yesterday)
                ->count(),
        ];

        // This month stats
        $thisMonthStats = [
            'sales' => Transaction::where('status', 'completed')
                ->whereDate('created_at', '>=', $thisMonth)
                ->sum('total_amount'),
            'transactions' => Transaction::where('status', 'completed')
                ->whereDate('created_at', '>=', $thisMonth)
                ->count(),
        ];

        // Last month stats for comparison
        $lastMonthStats = [
            'sales' => Transaction::where('status', 'completed')
                ->whereDate('created_at', '>=', $lastMonth)
                ->whereDate('created_at', '<=', $lastMonthEnd)
                ->sum('total_amount'),
            'transactions' => Transaction::where('status', 'completed')
                ->whereDate('created_at', '>=', $lastMonth)
                ->whereDate('created_at', '<=', $lastMonthEnd)
                ->count(),
        ];

        // Active cash registers
        $activeCashRegisters = CashRegister::where('status', 'open')
            ->with('user')
            ->count();

        // Low stock products count
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        // Recent transactions
        $recentTransactions = Transaction::with(['user'])
            ->where('status', 'completed')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'cashier' => $transaction->user->name,
                    'total_amount' => $transaction->total_amount,
                    'formatted_total' => $transaction->formatted_total,
                    'created_at' => $transaction->created_at->format('H:i'),
                ];
            });

        return response()->json([
            'today' => $todayStats,
            'yesterday' => $yesterdayStats,
            'this_month' => $thisMonthStats,
            'last_month' => $lastMonthStats,
            'comparisons' => [
                'sales_vs_yesterday' => $yesterdayStats['sales'] > 0 ? 
                    (($todayStats['sales'] - $yesterdayStats['sales']) / $yesterdayStats['sales']) * 100 : 0,
                'transactions_vs_yesterday' => $yesterdayStats['transactions'] > 0 ? 
                    (($todayStats['transactions'] - $yesterdayStats['transactions']) / $yesterdayStats['transactions']) * 100 : 0,
                'sales_vs_last_month' => $lastMonthStats['sales'] > 0 ? 
                    (($thisMonthStats['sales'] - $lastMonthStats['sales']) / $lastMonthStats['sales']) * 100 : 0,
            ],
            'active_cash_registers' => $activeCashRegisters,
            'low_stock_products' => $lowStockCount,
            'recent_transactions' => $recentTransactions,
        ]);
    }
}