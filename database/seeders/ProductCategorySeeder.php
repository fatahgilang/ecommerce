<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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