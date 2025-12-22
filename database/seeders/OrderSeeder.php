<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = [
            [
                'customer_id' => 1,
                'product_id' => 1,
                'product_quantity' => 1,
                'total_price' => 15000000,
                'payment_method' => 'Credit Card',
                'status' => 'completed',
            ],
            [
                'customer_id' => 2,
                'product_id' => 2,
                'product_quantity' => 2,
                'total_price' => 6000000,
                'payment_method' => 'Bank Transfer',
                'status' => 'processing',
            ],
            [
                'customer_id' => 3,
                'product_id' => 3,
                'product_quantity' => 1,
                'total_price' => 3500000,
                'payment_method' => 'E-Wallet',
                'status' => 'pending',
            ],
        ];

        foreach ($orders as $order) {
            \App\Models\Order::create($order);
        }
    }
}
