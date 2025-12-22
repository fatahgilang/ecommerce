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
            AdminUserSeeder::class,
            CustomerSeeder::class,
            ShopSeeder::class,
            ProductSeeder::class,
            ProductCategorySeeder::class,
            OrderSeeder::class,
            ShipmentSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}