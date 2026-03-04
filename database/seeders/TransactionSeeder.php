<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\PaymentSplit;
use App\Models\CashRegister;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashRegisters = CashRegister::all();
        $products = Product::all();
        $users = User::all();

        if ($cashRegisters->isEmpty() || $products->isEmpty()) {
            $this->command->error('Required data not found! Please run other seeders first.');
            return;
        }

        // Generate transactions untuk setiap cash register
        foreach ($cashRegisters as $cashRegister) {
            $transactionCount = rand(5, 15);
            
            for ($i = 0; $i < $transactionCount; $i++) {
                
                // Random payment method
                $paymentMethods = ['cash', 'card', 'ewallet', 'transfer'];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                // Random transaction time within cash register period
                $transactionTime = $this->getRandomTransactionTime($cashRegister);
                
                // Create transaction with unique number
                $transactionNumber = $this->generateUniqueTransactionNumber($transactionTime);
                
                $transaction = Transaction::create([
                    'transaction_number' => $transactionNumber,
                    'cash_register_id' => $cashRegister->id,
                    'user_id' => $cashRegister->user_id,
                    'subtotal' => 0,
                    'tax_amount' => 0,
                    'discount_amount' => 0,
                    'total_amount' => 0,
                    'payment_method' => $paymentMethod,
                    'paid_amount' => 0,
                    'change_amount' => 0,
                    'status' => $this->getRandomStatus(),
                    'notes' => $this->getRandomNotes(),
                    'created_at' => $transactionTime,
                    'updated_at' => $transactionTime,
                ]);

                // Add random items to transaction
                $this->addRandomItemsToTransaction($transaction, $products);
                
                // Calculate totals
                $this->calculateTransactionTotals($transaction);
                
                // Add payment splits for split payments
                if ($paymentMethod === 'split') {
                    $this->addPaymentSplits($transaction);
                }
            }
        }

        $this->command->info('Transactions seeded successfully!');
    }

    private function getRandomTransactionTime(CashRegister $cashRegister): Carbon
    {
        $openTime = $cashRegister->opened_at;
        $closeTime = $cashRegister->closed_at ?? now();
        
        // Random time between open and close
        $randomMinutes = rand(0, $openTime->diffInMinutes($closeTime));
        return $openTime->copy()->addMinutes($randomMinutes);
    }

    private function getRandomStatus(): string
    {
        $statuses = ['completed', 'completed', 'completed', 'completed', 'cancelled', 'refunded'];
        return $statuses[array_rand($statuses)];
    }

    private function getRandomNotes(): ?string
    {
        $notes = [
            null,
            null,
            null,
            'Pelanggan reguler',
            'Pembayaran pas',
            'Minta struk',
            'Beli untuk hadiah',
            'Pelanggan baru',
            'Promo member',
            'Pembelian grosir',
        ];
        return $notes[array_rand($notes)];
    }

    private function addRandomItemsToTransaction(Transaction $transaction, $products): void
    {
        $itemCount = rand(1, 5);
        $selectedProducts = $products->random($itemCount);
        
        foreach ($selectedProducts as $product) {
            $quantity = rand(1, 3);
            $unitPrice = $product->product_price;
            $discountAmount = rand(0, 1) ? 0 : rand(1000, 5000); // 50% chance of item discount
            $totalPrice = ($unitPrice * $quantity) - $discountAmount;
            
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'discount_amount' => $discountAmount,
                'total_price' => $totalPrice,
            ]);
        }
    }

    private function calculateTransactionTotals(Transaction $transaction): void
    {
        $subtotal = $transaction->items->sum('total_price');
        $taxRate = rand(0, 1) ? 0 : 10; // 50% chance of tax
        $taxAmount = $subtotal * ($taxRate / 100);
        $discountAmount = rand(0, 1) ? 0 : rand(5000, 20000); // 50% chance of transaction discount
        $totalAmount = $subtotal + $taxAmount - $discountAmount;
        
        // Calculate paid amount and change
        $paidAmount = $totalAmount;
        $changeAmount = 0;
        
        if ($transaction->payment_method === 'cash') {
            // For cash payments, sometimes customer pays more
            if (rand(0, 1)) {
                $roundedAmount = ceil($totalAmount / 5000) * 5000; // Round up to nearest 5000
                $paidAmount = $roundedAmount;
                $changeAmount = $paidAmount - $totalAmount;
            }
        }
        
        $transaction->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
        ]);
    }

    private function addPaymentSplits(Transaction $transaction): void
    {
        $totalAmount = $transaction->total_amount;
        $cashAmount = rand(50000, $totalAmount * 0.7);
        $cardAmount = $totalAmount - $cashAmount;
        
        PaymentSplit::create([
            'transaction_id' => $transaction->id,
            'payment_method' => 'cash',
            'amount' => $cashAmount,
            'reference_number' => null,
        ]);
        
        PaymentSplit::create([
            'transaction_id' => $transaction->id,
            'payment_method' => 'card',
            'amount' => $cardAmount,
            'reference_number' => 'CARD-' . strtoupper(substr(md5($transaction->id), 0, 8)),
        ]);
    }

    private function generateUniqueTransactionNumber($transactionTime): string
    {
        $date = $transactionTime->format('Ymd');
        $sequence = 1;
        
        do {
            $transactionNumber = 'TRX-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            $exists = Transaction::where('transaction_number', $transactionNumber)->exists();
            if ($exists) {
                $sequence++;
            }
        } while ($exists);
        
        return $transactionNumber;
    }
}