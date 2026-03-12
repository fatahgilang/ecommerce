<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductDiscountSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get some products to apply discounts
        $products = Product::take(5)->get();

        if ($products->count() > 0) {
            // Apply percentage discount to first product
            $products[0]->applyDiscount(
                discountPercentage: 15,
                startDate: now()->toDateString(),
                endDate: now()->addDays(7)->toDateString()
            );

            // Apply fixed price discount to second product
            if ($products->count() > 1) {
                $originalPrice = $products[1]->product_price;
                $discountPrice = $originalPrice * 0.8; // 20% off
                
                $products[1]->applyDiscount(
                    discountPrice: $discountPrice,
                    startDate: now()->toDateString(),
                    endDate: now()->addDays(14)->toDateString()
                );
            }

            // Apply percentage discount to third product
            if ($products->count() > 2) {
                $products[2]->applyDiscount(
                    discountPercentage: 25,
                    startDate: now()->toDateString(),
                    endDate: now()->addDays(30)->toDateString()
                );
            }

            // Apply discount to fourth product (expired - for testing)
            if ($products->count() > 3) {
                $products[3]->applyDiscount(
                    discountPercentage: 10,
                    startDate: now()->subDays(10)->toDateString(),
                    endDate: now()->subDays(1)->toDateString()
                );
            }

            // Apply future discount to fifth product
            if ($products->count() > 4) {
                $products[4]->applyDiscount(
                    discountPercentage: 30,
                    startDate: now()->addDays(1)->toDateString(),
                    endDate: now()->addDays(15)->toDateString()
                );
            }
        }

        $this->command->info('Product discounts seeded successfully!');
    }
}