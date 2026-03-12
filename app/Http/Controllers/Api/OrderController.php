<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display all orders (for store management).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['product', 'transaction', 'processedBy']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate($request->get('per_page', 10));

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'product' => [
                    'id' => $order->product->id,
                    'name' => $order->product->product_name,
                    'image' => $order->product->image ? asset('storage/' . $order->product->image) : null,
                    'price' => $order->product->product_price,
                ],
                'quantity' => $order->product_quantity,
                'total_price' => $order->total_price,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'transaction_id' => $order->transaction_id,
                'processed_by' => $order->processedBy?->name,
                'processed_at' => $order->processed_at?->format('d M Y H:i'),
                'created_at' => $order->created_at->format('d M Y H:i'),
                'created_at_human' => $order->created_at->diffForHumans(),
            ];
        });

        return response()->json($orders);
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['product', 'transaction', 'processedBy']);

        return response()->json([
            'id' => $order->id,
            'product' => [
                'id' => $order->product->id,
                'name' => $order->product->product_name,
                'description' => $order->product->product_description,
                'image' => $order->product->image ? asset('storage/' . $order->product->image) : null,
                'price' => $order->product->product_price,
            ],
            'quantity' => $order->product_quantity,
            'total_price' => $order->total_price,
            'payment_method' => $order->payment_method,
            'status' => $order->status,
            'transaction_id' => $order->transaction_id,
            'transaction_number' => $order->transaction?->transaction_number,
            'processed_by' => $order->processedBy?->name,
            'processed_at' => $order->processed_at?->format('d M Y H:i'),
            'created_at' => $order->created_at->format('d M Y H:i'),
        ]);
    }

    /**
     * Get order statistics.
     */
    public function statistics(Request $request): JsonResponse
    {
        $query = Order::query();

        // Filter by date range if provided
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return response()->json([
            'total_orders' => $query->count(),
            'pending_orders' => $query->clone()->where('status', 'pending')->count(),
            'paid_orders' => $query->clone()->where('status', 'paid')->count(),
            'processing_orders' => $query->clone()->where('status', 'processing')->count(),
            'completed_orders' => $query->clone()->where('status', 'completed')->count(),
            'cancelled_orders' => $query->clone()->where('status', 'cancelled')->count(),
            'total_revenue' => $query->clone()->whereIn('status', ['paid', 'processing', 'completed'])->sum('total_price'),
        ]);
    }
}