<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Product;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('Required data not found! Please run ProductSeeder first.');
            return;
        }

        $orders = [];
        
        // Generate orders untuk 30 hari terakhir
        for ($day = 30; $day >= 0; $day--) {
            $orderDate = today()->subDays($day);
            $dailyOrderCount = rand(2, 8);
            
            for ($i = 0; $i < $dailyOrderCount; $i++) {
                $product = $products->random();
                $quantity = rand(1, 5);
                $totalPrice = $product->product_price * $quantity;
                
                $paymentMethods = ['transfer', 'cod', 'ewallet', 'credit_card'];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                $statuses = ['pending', 'processing', 'completed', 'cancelled'];
                $statusWeights = [10, 20, 60, 10]; // 60% completed, 20% processing, etc.
                $status = $this->getWeightedRandomStatus($statuses, $statusWeights);
                
                $orderTime = $orderDate->copy()->addHours(rand(8, 22))->addMinutes(rand(0, 59));
                
                $order = Order::create([
                    'product_id' => $product->id,
                    'product_quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'payment_method' => $paymentMethod,
                    'status' => $status,
                    'created_at' => $orderTime,
                    'updated_at' => $orderTime,
                ]);

                // Create shipment for non-cancelled orders
                if ($status !== 'cancelled') {
                    $this->createShipmentForOrder($order);
                }
                
                $orders[] = $order;
            }
        }

        $this->command->info('Orders seeded successfully! Created ' . count($orders) . ' orders.');
    }

    private function getWeightedRandomStatus(array $statuses, array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($statuses as $index => $status) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $status;
            }
        }
        
        return $statuses[0];
    }

    private function createShipmentForOrder(Order $order): void
    {
        $shipmentTypes = ['regular', 'express', 'same_day', 'next_day'];
        $shipmentType = $shipmentTypes[array_rand($shipmentTypes)];
        
        // Calculate delivery cost based on shipment type
        $deliveryCosts = [
            'regular' => rand(10000, 15000),
            'express' => rand(20000, 30000),
            'same_day' => rand(35000, 50000),
            'next_day' => rand(25000, 35000),
        ];
        
        $deliveryCost = $deliveryCosts[$shipmentType];
        $productPrice = $order->total_price;
        $discount = rand(0, 1) ? 0 : rand(5000, 15000); // 50% chance of shipping discount
        $finalPrice = $productPrice + $deliveryCost - $discount;
        
        $shipmentStatuses = ['pending', 'shipped', 'delivered'];
        $shipmentStatus = $this->getShipmentStatusBasedOnOrder($order->status);
        
        // Generate random address
        $addresses = [
            'Jl. Melati No. 15, RT 05/RW 02, Kelurahan Cibinong, Bogor',
            'Jl. Mawar No. 22, RT 03/RW 01, Kelurahan Depok Jaya, Depok',
            'Jl. Anggrek No. 8, RT 02/RW 03, Kelurahan Margonda, Depok',
            'Jl. Flamboyan No. 33, RT 01/RW 04, Kelurahan Citeureup, Bogor',
            'Jl. Kenanga No. 7, RT 04/RW 02, Kelurahan Pancoran Mas, Depok',
        ];
        
        Shipment::create([
            'order_id' => $order->id,
            'shipment_type' => $shipmentType,
            'payment_method' => $order->payment_method,
            'customers_address' => $addresses[array_rand($addresses)],
            'product_price' => $productPrice,
            'delivery_cost' => $deliveryCost,
            'discount' => $discount,
            'final_price' => $finalPrice,
            'status' => $shipmentStatus,
        ]);
    }

    private function getShipmentStatusBasedOnOrder(string $orderStatus): string
    {
        switch ($orderStatus) {
            case 'pending':
                return 'pending';
            case 'processing':
                return rand(0, 1) ? 'pending' : 'shipped';
            case 'completed':
                $statuses = ['shipped', 'delivered', 'delivered', 'delivered']; // 75% delivered
                return $statuses[array_rand($statuses)];
            default:
                return 'pending';
        }
    }
}