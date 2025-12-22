<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          $reviews = [
            [
                'product_id' => 1,
                'customer_id' => 1,
                'review' => 'Amazing smartphone! The camera quality is outstanding and the battery life is incredible.',
                'rating' => 5,
                'is_verified_purchase' => true,
            ],
            [
                'product_id' => 1,
                'customer_id' => 2,
                'review' => 'Great phone but a bit pricey. Overall satisfied with the purchase.',
                'rating' => 4,
                'is_verified_purchase' => true,
            ],
            [
                'product_id' => 2,
                'customer_id' => 3,
                'review' => 'Best headphones I have ever owned. Noise cancellation is perfect!',
                'rating' => 5,
                'is_verified_purchase' => true,
            ],
            [
                'product_id' => 3,
                'customer_id' => 4,
                'review' => 'Comfortable and fits perfectly. Good quality cotton material.',
                'rating' => 5,
                'is_verified_purchase' => true,
            ],
            [
                'product_id' => 4,
                'customer_id' => 5,
                'review' => 'Very comfortable sneakers. Perfect for daily wear.',
                'rating' => 4,
                'is_verified_purchase' => true,
            ],
            [
                'product_id' => 5,
                'customer_id' => 1,
                'review' => 'Good quality and fast delivery. Highly recommend!',
                'rating' => 5,
                'is_verified_purchase' => true,
           ],
        ];

        foreach ($reviews as $review) {
            \App\Models\Review::create($review);
        }
    }
}