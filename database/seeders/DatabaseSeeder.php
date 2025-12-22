<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Order;
use App\Models\Review;
use App\Models\Shipment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CustomerSeeder::class,
            ShopSeeder::class,
            ProductSeeder::class,
            ProductCategorySeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            ShipmentSeeder::class,
        ]);
    }
}

// ========== CustomerSeeder ==========
class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Surabaya',
                'date_of_birth' => '1990-05-15',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '081234567891',
                'address' => 'Jl. Pahlawan No. 456, Jakarta',
                'date_of_birth' => '1992-08-20',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'phone' => '081234567892',
                'address' => 'Jl. Diponegoro No. 789, Bandung',
                'date_of_birth' => '1988-03-10',
            ],
            [
                'name' => 'Alice Williams',
                'email' => 'alice@example.com',
                'phone' => '081234567893',
                'address' => 'Jl. Sudirman No. 321, Malang',
                'date_of_birth' => '1995-12-25',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'phone' => '081234567894',
                'address' => 'Jl. Gatot Subroto No. 654, Yogyakarta',
                'date_of_birth' => '1993-07-18',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}

// ========== ShopSeeder ==========
class ShopSeeder extends Seeder
{
    public function run(): void
    {
        $shops = [
            [
                'shop_name' => 'TechStore',
                'description' => 'Your one-stop shop for all tech gadgets and electronics',
                'address' => 'Jl. Teknologi No. 100, Surabaya',
                'phone' => '0311234567',
                'email' => 'info@techstore.com',
            ],
            [
                'shop_name' => 'Fashion Hub',
                'description' => 'Trendy fashion and accessories for everyone',
                'address' => 'Jl. Fashion No. 200, Jakarta',
                'phone' => '0217654321',
                'email' => 'contact@fashionhub.com',
            ],
            [
                'shop_name' => 'Home Essentials',
                'description' => 'Everything you need for your home',
                'address' => 'Jl. Rumah No. 300, Bandung',
                'phone' => '0229876543',
                'email' => 'support@homeessentials.com',
            ],
            [
                'shop_name' => 'Sports Center',
                'description' => 'Quality sports equipment and apparel',
                'address' => 'Jl. Olahraga No. 400, Malang',
                'phone' => '0341112233',
                'email' => 'info@sportscenter.com',
            ],
        ];

        foreach ($shops as $shop) {
            Shop::create($shop);
        }
    }
}

// ========== ProductSeeder ==========
class ProductSeeder extends Seeder
{
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

// ========== ProductCategorySeeder ==========
class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['product_id' => 1, 'category_name' => 'Electronics'],
            ['product_id' => 1, 'category_name' => 'Smartphones'],
            ['product_id' => 2, 'category_name' => 'Electronics'],
            ['product_id' => 2, 'category_name' => 'Audio'],
            ['product_id' => 3, 'category_name' => 'Electronics'],
            ['product_id' => 3, 'category_name' => 'Laptops'],
            ['product_id' => 4, 'category_name' => 'Electronics'],
            ['product_id' => 4, 'category_name' => 'Wearables'],
            ['product_id' => 5, 'category_name' => 'Fashion'],
            ['product_id' => 5, 'category_name' => 'Clothing'],
            ['product_id' => 6, 'category_name' => 'Fashion'],
            ['product_id' => 6, 'category_name' => 'Clothing'],
            ['product_id' => 7, 'category_name' => 'Fashion'],
            ['product_id' => 7, 'category_name' => 'Outerwear'],
            ['product_id' => 8, 'category_name' => 'Fashion'],
            ['product_id' => 8, 'category_name' => 'Footwear'],
            ['product_id' => 9, 'category_name' => 'Home & Kitchen'],
            ['product_id' => 10, 'category_name' => 'Home & Kitchen'],
            ['product_id' => 11, 'category_name' => 'Home Appliances'],
            ['product_id' => 12, 'category_name' => 'Sports'],
            ['product_id' => 12, 'category_name' => 'Fitness'],
            ['product_id' => 13, 'category_name' => 'Sports'],
            ['product_id' => 13, 'category_name' => 'Fitness'],
            ['product_id' => 14, 'category_name' => 'Sports'],
            ['product_id' => 14, 'category_name' => 'Footwear'],
            ['product_id' => 15, 'category_name' => 'Sports'],
            ['product_id' => 15, 'category_name' => 'Accessories'],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}

// ========== OrderSeeder (Create some sample orders) ==========
class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Sample orders will be created in ShipmentSeeder
    }
}

// ========== ReviewSeeder ==========
class ReviewSeeder extends Seeder
{
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
                'product_id' => 5,
                'customer_id' => 4,
                'review' => 'Comfortable and fits perfectly. Good quality cotton material.',
                'rating' => 5,
                'is_verified_purchase' => true,
            ],
            [
                'product_id' => 8,
                'customer_id' => 5,
                'review' => 'Very comfortable sneakers. Perfect for daily wear.',
                'rating' => 4,
                'is_verified_purchase' => true,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}

// ========== ShipmentSeeder ==========
class ShipmentSeeder extends Seeder
{
    public function run(): void
    {
        // Sample shipments will be added after creating orders
    }
}