<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CashRegister;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;

class CashierSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create cashier users
        $cashier1 = User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir1@example.com',
            'password' => bcrypt('password'),
        ]);

        $cashier2 = User::create([
            'name' => 'Kasir 2', 
            'email' => 'kasir2@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create cash registers
        $register1 = CashRegister::create([
            'register_name' => 'Kasir Utama',
            'user_id' => $cashier1->id,
            'opening_balance' => 500000,
            'total_sales' => 0,
            'total_cash' => 0,
            'total_card' => 0,
            'total_ewallet' => 0,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        $register2 = CashRegister::create([
            'register_name' => 'Kasir 2',
            'user_id' => $cashier2->id,
            'opening_balance' => 300000,
            'total_sales' => 0,
            'total_cash' => 0,
            'total_card' => 0,
            'total_ewallet' => 0,
            'status' => 'closed',
            'opened_at' => now()->subHours(8),
            'closed_at' => now()->subHours(1),
            'closing_balance' => 450000,
        ]);

        // Create discounts
        Discount::create([
            'name' => 'Diskon Hari Ini',
            'code' => 'TODAY10',
            'type' => 'percentage',
            'value' => 10,
            'minimum_amount' => 50000,
            'maximum_discount' => 25000,
            'start_date' => today(),
            'end_date' => today()->addDays(7),
            'usage_limit' => 100,
            'used_count' => 0,
            'is_active' => true,
        ]);

        Discount::create([
            'name' => 'Potongan 20rb',
            'code' => 'HEMAT20',
            'type' => 'fixed_amount',
            'value' => 20000,
            'minimum_amount' => 100000,
            'start_date' => today(),
            'end_date' => today()->addDays(30),
            'usage_limit' => 50,
            'used_count' => 0,
            'is_active' => true,
        ]);

        Discount::create([
            'name' => 'Member Discount',
            'code' => null, // No code, applied manually
            'type' => 'percentage',
            'value' => 5,
            'minimum_amount' => 0,
            'start_date' => today()->subDays(30),
            'end_date' => today()->addDays(365),
            'usage_limit' => null,
            'used_count' => 0,
            'is_active' => true,
        ]);

        // Create sample transactions if products exist
        $products = Product::limit(3)->get();
        
        if ($products->count() > 0) {
            // Sample transaction 1
            $transaction1 = Transaction::create([
                'cash_register_id' => $register1->id,
                'user_id' => $cashier1->id,
                'subtotal' => 150000,
                'tax_amount' => 15000,
                'discount_amount' => 0,
                'total_amount' => 165000,
                'payment_method' => 'cash',
                'paid_amount' => 200000,
                'change_amount' => 35000,
                'status' => 'completed',
                'notes' => 'Transaksi demo',
            ]);

            // Add items to transaction 1
            foreach ($products->take(2) as $product) {
                TransactionItem::create([
                    'transaction_id' => $transaction1->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'unit_price' => $product->product_price,
                    'discount_amount' => 0,
                    'total_price' => $product->product_price,
                ]);
            }

            // Sample transaction 2 with discount
            $transaction2 = Transaction::create([
                'cash_register_id' => $register1->id,
                'user_id' => $cashier1->id,
                'subtotal' => 75000,
                'tax_amount' => 7500,
                'discount_amount' => 7500, // 10% discount
                'total_amount' => 75000,
                'payment_method' => 'card',
                'paid_amount' => 75000,
                'change_amount' => 0,
                'status' => 'completed',
                'notes' => 'Transaksi dengan diskon TODAY10',
            ]);

            // Add item to transaction 2
            if ($products->count() > 2) {
                TransactionItem::create([
                    'transaction_id' => $transaction2->id,
                    'product_id' => $products->get(2)->id,
                    'quantity' => 2,
                    'unit_price' => $products->get(2)->product_price,
                    'discount_amount' => 0,
                    'total_price' => $products->get(2)->product_price * 2,
                ]);
            }

            // Update cash register totals
            $register1->updateTotals();
        }

        $this->command->info('Cashier system seeded successfully!');
    }
}