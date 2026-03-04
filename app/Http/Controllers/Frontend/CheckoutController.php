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

                // Create order
                $order = Order::create([
                    'product_id' => $product->id,
                    'product_quantity' => $item['quantity'],
                    'total_price' => $itemTotal,
                    'payment_method' => $validated['payment_method'],
                    'status' => 'pending',
                ]);

                \Log::info('Order created', ['order_id' => $order->id]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);

                $orders[] = [
                    'order_id' => $order->id,
                    'product_name' => $product->product_name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $itemTotal,
                ];
            }

            DB::commit();
            
            \Log::info('Checkout completed successfully', [
                'total_orders' => count($orders),
                'subtotal' => $subtotal
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'data' => [
                    'orders' => $orders,
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'payment_method' => $validated['payment_method'],
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
