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
     * Display customer's orders.
     */
    public function index(Request $request): JsonResponse
    {
        $customerId = $request->get('customer_id');
        
        if (!$customerId) {
            return response()->json([
                'message' => 'Customer ID is required'
            ], 400);
        }

        $query = Order::with(['product', 'shipment'])
            ->where('customer_id', $customerId);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate($request->get('per_page', 10));

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'product' => [
                    'id' => $order->product->id,
                    'name' => $order->product->product_name,
                    'image' => $order->product->image ? asset('storage/' . $order->product->image) : null,
                    'price' => $order->product->product_price,
                ],
                'quantity' => $order->product_quantity,
                'total_price' => $order->total_price,
                'formatted_total' => $order->formatted_total,
                'payment_method' => $order->payment_method,
                'status' => $order->status,
                'can_be_cancelled' => $order->canBeCancelled(),
                'can_be_reviewed' => $order->canBeReviewed(),
                'shipment' => $order->shipment ? [
                    'tracking_number' => $order->shipment->tracking_number,
                    'status' => $order->shipment->status,
                    'shipment_type' => $order->shipment->shipment_type,
                ] : null,
                'created_at' => $order->created_at->format('d M Y H:i'),
                'created_at_human' => $order->created_at->diffForHumans(),
            ];
        });

        return response()->json($orders);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'product_quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:transfer,cod,ewallet,credit_card',
            'notes' => 'nullable|string',
            'shipping_address' => 'required|string',
            'recipient_name' => 'required|string',
            'recipient_phone' => 'required|string',
            'shipment_type' => 'required|string',
            'delivery_cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Check product availability
            $product = Product::findOrFail($validated['product_id']);
            
            if (!$product->in_stock) {
                return response()->json([
                    'message' => 'Product is out of stock'
                ], 422);
            }

            if ($product->stock < $validated['product_quantity']) {
                return response()->json([
                    'message' => 'Insufficient stock. Available: ' . $product->stock
                ], 422);
            }

            // Calculate total price
            $totalPrice = $product->product_price * $validated['product_quantity'];

            // Create order
            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'product_id' => $validated['product_id'],
                'product_quantity' => $validated['product_quantity'],
                'total_price' => $totalPrice,
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
            ]);

            // Reduce product stock
            $product->reduceStock($validated['product_quantity']);

            // Create shipment
            $finalPrice = $totalPrice + $validated['delivery_cost'];
            
            $shipment = $order->shipment()->create([
                'shipment_type' => $validated['shipment_type'],
                'payment_method' => $validated['payment_method'],
                'customers_address' => $validated['shipping_address'],
                'recipient_name' => $validated['recipient_name'],
                'recipient_phone' => $validated['recipient_phone'],
                'product_price' => $totalPrice,
                'delivery_cost' => $validated['delivery_cost'],
                'discount' => 0,
                'tax' => 0,
                'final_price' => $finalPrice,
                'status' => 'pending',
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'total_price' => $order->total_price,
                    'status' => $order->status,
                    'tracking_number' => $shipment->tracking_number,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to create order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['customer', 'product.shop', 'shipment', 'review']);

        return response()->json([
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer' => [
                'id' => $order->customer->id,
                'name' => $order->customer->name,
                'email' => $order->customer->email,
                'phone' => $order->customer->phone,
            ],
            'product' => [
                'id' => $order->product->id,
                'name' => $order->product->product_name,
                'description' => $order->product->product_description,
                'image' => $order->product->image ? asset('storage/' . $order->product->image) : null,
                'price' => $order->product->product_price,
                'shop' => [
                    'name' => $order->product->shop->shop_name,
                    'phone' => $order->product->shop->phone,
                ],
            ],
            'quantity' => $order->product_quantity,
            'total_price' => $order->total_price,
            'formatted_total' => $order->formatted_total,
            'payment_method' => $order->payment_method,
            'status' => $order->status,
            'notes' => $order->notes,
            'can_be_cancelled' => $order->canBeCancelled(),
            'can_be_reviewed' => $order->canBeReviewed(),
            'shipment' => $order->shipment ? [
                'tracking_number' => $order->shipment->tracking_number,
                'shipment_type' => $order->shipment->shipment_type,
                'status' => $order->shipment->status,
                'recipient_name' => $order->shipment->recipient_name,
                'recipient_phone' => $order->shipment->recipient_phone,
                'address' => $order->shipment->customers_address,
                'delivery_cost' => $order->shipment->delivery_cost,
                'final_price' => $order->shipment->final_price,
                'picked_up_at' => $order->shipment->picked_up_at?->format('d M Y H:i'),
                'delivered_at' => $order->shipment->delivered_at?->format('d M Y H:i'),
            ] : null,
            'review' => $order->review,
            'confirmed_at' => $order->confirmed_at?->format('d M Y H:i'),
            'shipped_at' => $order->shipped_at?->format('d M Y H:i'),
            'completed_at' => $order->completed_at?->format('d M Y H:i'),
            'cancelled_at' => $order->cancelled_at?->format('d M Y H:i'),
            'cancellation_reason' => $order->cancellation_reason,
            'created_at' => $order->created_at->format('d M Y H:i'),
        ]);
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, Order $order): JsonResponse
    {
        if (!$order->canBeCancelled()) {
            return response()->json([
                'message' => 'This order cannot be cancelled'
            ], 422);
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        try {
            DB::beginTransaction();

            $order->updateStatus('cancelled', $validated['reason']);

            // Restore product stock
            $product = $order->product;
            $product->increment('stock', $order->product_quantity);

            // Update shipment status
            if ($order->shipment) {
                $order->shipment->update(['status' => 'returned']);
            }

            DB::commit();

            return response()->json([
                'message' => 'Order cancelled successfully',
                'order' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Failed to cancel order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get order statistics for customer.
     */
    public function statistics(Request $request): JsonResponse
    {
        $customerId = $request->get('customer_id');
        
        if (!$customerId) {
            return response()->json([
                'message' => 'Customer ID is required'
            ], 400);
        }

        $orders = Order::where('customer_id', $customerId);

        return response()->json([
            'total_orders' => $orders->count(),
            'pending_orders' => $orders->clone()->where('status', 'pending')->count(),
            'completed_orders' => $orders->clone()->where('status', 'completed')->count(),
            'cancelled_orders' => $orders->clone()->where('status', 'cancelled')->count(),
            'total_spent' => $orders->clone()->where('status', 'completed')->sum('total_price'),
        ]);
    }
}