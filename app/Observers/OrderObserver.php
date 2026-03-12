<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\CashRegister;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderObserver
{
    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if status changed to 'paid' and no transaction exists yet
        if ($order->isDirty('status') && 
            $order->status === 'paid' && 
            $order->getOriginal('status') !== 'paid' &&
            !$order->transaction_id) {
            
            $this->createTransactionFromOrder($order);
        }
    }

    /**
     * Create a transaction from a paid order
     */
    private function createTransactionFromOrder(Order $order): void
    {
        try {
            DB::beginTransaction();

            // Get the current user (kasir) or use a default system user
            $kasirId = Auth::id() ?? 1; // Fallback to admin user if no auth
            
            // Get an active cash register or create a default one
            $cashRegister = CashRegister::where('status', 'open')->first();
            if (!$cashRegister) {
                // Create a default cash register if none exists
                $cashRegister = CashRegister::create([
                    'register_name' => 'Kasir Utama',
                    'user_id' => $kasirId,
                    'opening_balance' => 0,
                    'status' => 'open',
                    'opened_at' => now(),
                ]);
            }

            // Map payment methods from order to transaction format
            $paymentMethodMap = [
                'cash' => 'cash',
                'bca' => 'transfer',
                'mandiri' => 'transfer', 
                'bri' => 'transfer',
                'gopay' => 'ewallet',
                'ovo' => 'ewallet',
                'dana' => 'ewallet',
            ];

            $transactionPaymentMethod = $paymentMethodMap[$order->payment_method] ?? 'cash';

            // Create the transaction
            $transaction = Transaction::create([
                'cash_register_id' => $cashRegister->id,
                'user_id' => $kasirId,
                'subtotal' => $order->total_price,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $order->total_price,
                'payment_method' => $transactionPaymentMethod,
                'paid_amount' => $order->total_price,
                'change_amount' => 0,
                'status' => 'completed',
                'notes' => "Transaksi otomatis dari pesanan #{$order->id} - {$order->payment_method}",
            ]);

            // Create transaction item
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $order->product_id,
                'quantity' => $order->product_quantity,
                'unit_price' => $order->product->getCurrentPrice(), // Use current price (with discount)
                'discount_amount' => 0,
                'total_price' => $order->total_price,
            ]);

            // Update the order with transaction info
            $order->update([
                'transaction_id' => $transaction->id,
                'processed_by' => $kasirId,
                'processed_at' => now(),
            ]);

            // Update cash register balance based on payment method
            $cashRegister->increment('total_sales', $order->total_price);
            
            switch ($transactionPaymentMethod) {
                case 'cash':
                    $cashRegister->increment('total_cash', $order->total_price);
                    break;
                case 'transfer':
                    // For bank transfers, we don't add to cash register balance
                    // but we track it in total_sales
                    break;
                case 'ewallet':
                    $cashRegister->increment('total_ewallet', $order->total_price);
                    break;
                case 'card':
                    $cashRegister->increment('total_card', $order->total_price);
                    break;
            }

            DB::commit();

            \Log::info("Transaction created automatically for order #{$order->id}", [
                'order_id' => $order->id,
                'transaction_id' => $transaction->id,
                'kasir_id' => $kasirId,
                'amount' => $order->total_price
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error("Failed to create transaction for order #{$order->id}", [
                'error' => $e->getMessage(),
                'order_id' => $order->id
            ]);
        }
    }
}