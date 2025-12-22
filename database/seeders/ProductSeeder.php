<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $products = [
            // TechStore Products
            [
                'shop_id' => 1,
                'product_name' => 'Smartphone Pro X1',
                'product_description' => 'Latest flagship smartphone with advanced features and stunning display',
                'product_price' => 8999000,
                'price_per_unit' => 8999000,
                'unit' => 'pcs',
                'stock' => 50,
                'weight' => 0.2,
            ],
            [
                'shop_id' => 1,
                'product_name' => 'Wireless Headphones Ultra',
                'product_description' => 'Premium noise-cancelling headphones with 30h battery life',
                'product_price' => 1299000,
                'price_per_unit' => 1299000,
                'unit' => 'pcs',
                'stock' => 100,
                'weight' => 0.3,
            ],
            [
                'shop_id' => 1,
                'product_name' => 'Laptop Gaming Beast',
                'product_description' => 'Powerful gaming laptop with RTX graphics and RGB keyboard',
                'product_price' => 15999000,
                'price_per_unit' => 15999000,
                'unit' => 'pcs',
                'stock' => 25,
                'weight' => 2.5,
            ],
            [
                'shop_id' => 1,
                'product_name' => 'Smart Watch Fit Pro',
                'product_description' => 'Advanced fitness tracker with heart rate monitor and GPS',
                'product_price' => 2499000,
                'price_per_unit' => 2499000,
                'unit' => 'pcs',
                'stock' => 75,
                'weight' => 0.1,
            ],
            
            // Fashion Hub Products
            [
                'shop_id' => 2,
                'product_name' => 'Premium Cotton T-Shirt',
                'product_description' => 'Comfortable and stylish cotton t-shirt in various colors',
                'product_price' => 149000,
                'price_per_unit' => 149000,
                'unit' => 'pcs',
                'stock' => 200,
                'weight' => 0.2,
            ],
            [
                'shop_id' => 2,
                'product_name' => 'Designer Jeans Collection',
                'product_description' => 'High-quality denim jeans with perfect fit',
                'product_price' => 499000,
                'price_per_unit' => 499000,
                'unit' => 'pcs',
                'stock' => 150,
                'weight' => 0.5,
            ],
            [
                'shop_id' => 2,
                'product_name' => 'Leather Jacket Premium',
                'product_description' => 'Genuine leather jacket for a classic look',
                'product_price' => 1999000,
                'price_per_unit' => 1999000,
                'unit' => 'pcs',
                'stock' => 30,
                'weight' => 1.2,
            ],
            [
                'shop_id' => 2,
                'product_name' => 'Sneakers Sport Edition',
                'product_description' => 'Comfortable sneakers for sports and casual wear',
                'product_price' => 799000,
                'price_per_unit' => 799000,
                'unit' => 'pair',
                'stock' => 80,
                'weight' => 0.8,
            ],
            
            // Home Essentials Products
            [
                'shop_id' => 3,
                'product_name' => 'Coffee Maker Deluxe',
                'product_description' => 'Automatic coffee maker with multiple brewing options',
                'product_price' => 899000,
                'price_per_unit' => 899000,
                'unit' => 'pcs',
                'stock' => 40,
                'weight' => 2.0,
            ],
            [
                'shop_id' => 3,
                'product_name' => 'Ceramic Dinnerware Set',
                'product_description' => 'Complete 24-piece dinnerware set for family',
                'product_price' => 599000,
                'price_per_unit' => 599000,
                'unit' => 'set',
                'stock' => 60,
                'weight' => 5.0,
            ],
            [
                'shop_id' => 3,
                'product_name' => 'Vacuum Cleaner Robot',
                'product_description' => 'Smart robot vacuum with automatic charging',
                'product_price' => 3499000,
                'price_per_unit' => 3499000,
                'unit' => 'pcs',
                'stock' => 20,
                'weight' => 3.5,
            ],
            
            // Sports Center Products
            [
                'shop_id' => 4,
                'product_name' => 'Yoga Mat Premium',
                'product_description' => 'Non-slip yoga mat with carrying strap',
                'product_price' => 299000,
                'price_per_unit' => 299000,
                'unit' => 'pcs',
                'stock' => 100,
                'weight' => 1.0,
            ],
            [
                'shop_id' => 4,
                'product_name' => 'Dumbbell Set 20kg',
                'product_description' => 'Adjustable dumbbell set for home workout',
                'product_price' => 899000,
                'price_per_unit' => 899000,
                'unit' => 'set',
                'stock' => 50,
                'weight' => 20.0,
            ],
            [
                'shop_id' => 4,
                'product_name' => 'Running Shoes Pro',
                'product_description' => 'Professional running shoes with cushion technology',
                'product_price' => 1299000,
                'price_per_unit' => 1299000,
                'unit' => 'pair',
                'stock' => 70,
                'weight' => 0.6,
            ],
            [
                'shop_id' => 4,
                'product_name' => 'Sports Bag Backpack',
                'product_description' => 'Spacious backpack for gym and travel',
                'product_price' => 399000,
                'price_per_unit' => 399000,
                'unit' => 'pcs',
                'stock' => 90,
                'weight' => 0.5,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
