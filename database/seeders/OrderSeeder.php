<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
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
                $totalPrice = $product->getCurrentPrice() * $quantity;
                
                $paymentMethods = ['cash', 'bca', 'mandiri', 'bri', 'gopay', 'ovo', 'dana'];
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                
                $statuses = ['pending', 'paid', 'processing', 'completed', 'cancelled'];
                $statusWeights = [5, 15, 20, 50, 10]; // 50% completed, 20% processing, etc.
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
}