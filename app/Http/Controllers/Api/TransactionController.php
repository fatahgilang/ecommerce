<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\CashRegister;
use App\Models\Product;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    /**
     * Get transactions for cash register
     */
    public function index(Request $request): JsonResponse
    {
        $query = Transaction::with(['customer', 'user', 'items.product']);

        if ($request->has('cash_register_id')) {
            $query->where('cash_register_id', $request->cash_register_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate($request->get('per_page', 10));

        $transactions->getCollection()->transform(function ($transaction) {
            return [
                'id' => $transaction->id,
                'transaction_number' => $transaction->transaction_number,
                'customer' => $transaction->customer ? [
                    'id' => $transaction->customer->id,
                    'name' => $transaction->customer->name,
                ] : null,
                'cashier' => $transaction->user->name,
                'total_amount' => $transaction->total_amount,
                'formatted_total' => $transaction->formatted_total,
                'payment_method' => $transaction->payment_method,
                'status' => $transaction->status,
                'items_count' => $transaction->items->count(),
                'created_at' => $transaction->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json($transactions);
    }

    /**
     * Create new transaction
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'cash_register_id' => 'required|exists:cash_registers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,ewallet,transfer,split',
            'paid_amount' => 'required|numeric|min:0',
            'discount_code' => 'nullable|string',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'notes' => 'nullable|string',
            'payment_splits' => 'nullable|array',
            'payment_splits.*.payment_method' => 'required_with:payment_splits|in:cash,card,ewallet,transfer',
            'payment_splits.*.amount' => 'required_with:payment_splits|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Check cash register is open
            $cashRegister = CashRegister::findOrFail($request->cash_register_id);
            if (!$cashRegister->isOpen()) {
                return response()->json([
                    'message' => 'Kasir tidak terbuka'
                ], 422);
            }

            // Create transaction
            $transaction = Transaction::create([
                'cash_register_id' => $request->cash_register_id,
                'customer_id' => $request->customer_id,
                'user_id' => $request->user_id,
                'payment_method' => $request->payment_method,
                'paid_amount' => $request->paid_amount,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'subtotal' => 0,
                'total_amount' => 0,
                'change_amount' => 0,
                'notes' => $request->notes,
                'status' => 'pending',
            ]);

            // Add items
            $subtotal = 0;
            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Check stock
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->product_name}");
                }

                $discountAmount = $itemData['discount_amount'] ?? 0;
                $item = $transaction->addItem($product, $itemData['quantity'], $discountAmount);
                $subtotal += $item->total_price;

                // Reduce stock
                $product->decrement('stock', $itemData['quantity']);
            }

            // Apply discount code if provided
            $discountAmount = 0;
            if ($request->discount_code) {
                $discount = Discount::active()->byCode($request->discount_code)->first();
                if ($discount && $discount->isValid($subtotal)) {
                    $discountAmount = $discount->calculateDiscount($subtotal);
                    $discount->use();
                }
            }

            // Calculate tax
            $taxRate = $request->tax_rate ?? 0;
            $taxAmount = ($subtotal - $discountAmount) * ($taxRate / 100);

            // Update transaction totals
            $transaction->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $subtotal + $taxAmount - $discountAmount,
                'change_amount' => max(0, $request->paid_amount - ($subtotal + $taxAmount - $discountAmount)),
                'status' => 'completed',
            ]);

            // Handle split payments
            if ($request->payment_method === 'split' && $request->payment_splits) {
                foreach ($request->payment_splits as $split) {
                    $transaction->paymentSplits()->create([
                        'payment_method' => $split['payment_method'],
                        'amount' => $split['amount'],
                        'reference_number' => $split['reference_number'] ?? null,
                    ]);
                }
            }

            // Update cash register totals
            $cashRegister->updateTotals();

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil dibuat',
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'total_amount' => $transaction->total_amount,
                    'formatted_total' => $transaction->formatted_total,
                    'change_amount' => $transaction->change_amount,
                    'status' => $transaction->status,
                ]
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Gagal membuat transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction details
     */
    public function show(Transaction $transaction): JsonResponse
    {
        $transaction->load(['customer', 'user', 'items.product', 'paymentSplits', 'cashRegister']);

        return response()->json([
            'id' => $transaction->id,
            'transaction_number' => $transaction->transaction_number,
            'customer' => $transaction->customer ? [
                'id' => $transaction->customer->id,
                'name' => $transaction->customer->name,
                'phone' => $transaction->customer->phone,
            ] : null,
            'cashier' => [
                'id' => $transaction->user->id,
                'name' => $transaction->user->name,
            ],
            'cash_register' => [
                'id' => $transaction->cashRegister->id,
                'name' => $transaction->cashRegister->register_name,
            ],
            'items' => $transaction->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->product_name,
                        'image' => $item->product->image ? asset('storage/' . $item->product->image) : null,
                    ],
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'formatted_unit_price' => $item->formatted_unit_price,
                    'discount_amount' => $item->discount_amount,
                    'total_price' => $item->total_price,
                    'formatted_total' => $item->formatted_total,
                ];
            }),
            'payment_splits' => $transaction->paymentSplits->map(function ($split) {
                return [
                    'payment_method' => $split->payment_method,
                    'amount' => $split->amount,
                    'formatted_amount' => $split->formatted_amount,
                    'reference_number' => $split->reference_number,
                ];
            }),
            'subtotal' => $transaction->subtotal,
            'tax_amount' => $transaction->tax_amount,
            'discount_amount' => $transaction->discount_amount,
            'total_amount' => $transaction->total_amount,
            'formatted_total' => $transaction->formatted_total,
            'paid_amount' => $transaction->paid_amount,
            'change_amount' => $transaction->change_amount,
            'payment_method' => $transaction->payment_method,
            'status' => $transaction->status,
            'notes' => $transaction->notes,
            'can_be_refunded' => $transaction->canBeRefunded(),
            'created_at' => $transaction->created_at->format('d M Y H:i'),
        ]);
    }

    /**
     * Cancel transaction
     */
    public function cancel(Transaction $transaction): JsonResponse
    {
        if ($transaction->status !== 'completed') {
            return response()->json([
                'message' => 'Hanya transaksi yang selesai yang dapat dibatalkan'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Restore stock
            foreach ($transaction->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Update transaction status
            $transaction->update(['status' => 'cancelled']);

            // Update cash register totals
            $transaction->cashRegister->updateTotals();

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil dibatalkan',
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'status' => $transaction->status,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Gagal membatalkan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Refund transaction
     */
    public function refund(Request $request, Transaction $transaction): JsonResponse
    {
        if (!$transaction->canBeRefunded()) {
            return response()->json([
                'message' => 'Transaksi ini tidak dapat dikembalikan'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Restore stock
            foreach ($transaction->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }

            // Update transaction status
            $transaction->update([
                'status' => 'refunded',
                'notes' => ($transaction->notes ? $transaction->notes . "\n\n" : '') . 
                          "REFUNDED: " . $request->reason
            ]);

            // Update cash register totals
            $transaction->cashRegister->updateTotals();

            DB::commit();

            return response()->json([
                'message' => 'Transaksi berhasil dikembalikan',
                'transaction' => [
                    'id' => $transaction->id,
                    'transaction_number' => $transaction->transaction_number,
                    'status' => $transaction->status,
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'message' => 'Gagal mengembalikan transaksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction receipt data
     */
    public function receipt(Transaction $transaction): JsonResponse
    {
        $transaction->load(['customer', 'user', 'items.product', 'cashRegister']);

        return response()->json([
            'receipt_data' => [
                'transaction_number' => $transaction->transaction_number,
                'date' => $transaction->created_at->format('d/m/Y H:i'),
                'cashier' => $transaction->user->name,
                'customer' => $transaction->customer?->name ?? 'Walk-in Customer',
                'items' => $transaction->items->map(function ($item) {
                    return [
                        'name' => $item->product->product_name,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total' => $item->total_price,
                    ];
                }),
                'subtotal' => $transaction->subtotal,
                'tax_amount' => $transaction->tax_amount,
                'discount_amount' => $transaction->discount_amount,
                'total_amount' => $transaction->total_amount,
                'paid_amount' => $transaction->paid_amount,
                'change_amount' => $transaction->change_amount,
                'payment_method' => $transaction->payment_method,
            ]
        ]);
    }
}