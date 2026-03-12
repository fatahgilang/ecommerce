<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        \Log::info('Checkout request received', ['data' => $request->all()]);
        
        $validated = $request->validate([
            'payment_method' => 'required|string',
            'discount_code' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        \Log::info('Validation passed', ['validated' => $validated]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $orders = [];

            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                \Log::info('Processing item', [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'quantity' => $item['quantity'],
                    'stock' => $product->stock
                ]);
                
                // Check stock
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->product_name} tidak mencukupi. Tersedia: {$product->stock}");
                }

                $itemTotal = $item['price'] * $item['quantity'];
                $subtotal += $itemTotal;

                $orders[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->product_name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $itemTotal,
                ];
            }

            // Handle discount code
            $discount = null;
            $discountAmount = 0;
            
            if (!empty($validated['discount_code'])) {
                $discount = \App\Models\Discount::active()
                    ->byCode($validated['discount_code'])
                    ->first();
                
                if ($discount && $discount->isValid($subtotal)) {
                    $discountAmount = $discount->calculateDiscount($subtotal);
                    \Log::info('Discount applied', [
                        'code' => $validated['discount_code'],
                        'discount_amount' => $discountAmount
                    ]);
                } else {
                    throw new \Exception('Kode diskon tidak valid atau tidak memenuhi syarat minimum pembelian');
                }
            }

            $finalTotal = $subtotal - $discountAmount;

            // Create orders
            foreach ($orders as $index => $orderData) {
                $product = Product::findOrFail($orderData['product_id']);
                
                // Create order
                $order = Order::create([
                    'product_id' => $product->id,
                    'product_quantity' => $orderData['quantity'],
                    'total_price' => $orderData['total'],
                    'payment_method' => $validated['payment_method'],
                    'status' => 'pending',
                    'discount_code' => $validated['discount_code'] ?? null,
                    'discount_amount' => $index === 0 ? $discountAmount : 0, // Apply discount to first order only
                ]);

                \Log::info('Order created', ['order_id' => $order->id]);

                // Update product stock
                $product->decrement('stock', $orderData['quantity']);

                $orders[$index]['order_id'] = $order->id;
            }

            // Mark discount as used if applied
            if ($discount && $discountAmount > 0) {
                $discount->use();
            }

            DB::commit();
            
            \Log::info('Checkout completed successfully', [
                'total_orders' => count($orders),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'final_total' => $finalTotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'data' => [
                    'orders' => $orders,
                    'subtotal' => $subtotal,
                    'discount_amount' => $discountAmount,
                    'total' => $finalTotal,
                    'payment_method' => $validated['payment_method'],
                    'discount_code' => $validated['discount_code'] ?? null,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Checkout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage(),
            ], 422);
        }
    }
}