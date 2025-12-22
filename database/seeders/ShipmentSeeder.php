<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shipments = [
            [
                'order_id' => 1,
                'shipment_type' => 'Express',
                'payment_method' => 'Credit Card',
                'customers_address' => 'Jl. Merdeka No. 123, Surabaya',
                'product_price' => 15000000,
                'delivery_cost' => 25000,
                'discount' => 0,
                'final_price' => 15025000,
                'status' => 'delivered',
            ],
            [
                'order_id' => 2,
                'shipment_type' => 'Standard',
                'payment_method' => 'Bank Transfer',
                'customers_address' => 'Jl. Pahlawan No. 456, Jakarta',
                'product_price' => 6000000,
                'delivery_cost' => 15000,
                'discount' => 0,
                'final_price' => 6015000,
                'status' => 'shipped',
            ],
            [
                'order_id' => 3,
                'shipment_type' => 'Same Day',
                'payment_method' => 'E-Wallet',
                'customers_address' => 'Jl. Diponegoro No. 789, Bandung',
                'product_price' => 3500000,
                'delivery_cost' => 30000,
                'discount' => 0,
                'final_price' => 3530000,
                'status' => 'pending',
            ],
        ];

        foreach ($shipments as $shipment) {
            \App\Models\Shipment::create($shipment);
        }
    }
}
