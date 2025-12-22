<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
            \App\Models\Shop::create($shop);
        }
    }
}
