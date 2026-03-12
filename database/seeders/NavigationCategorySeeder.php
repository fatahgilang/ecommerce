<?php

namespace Database\Seeders;

use App\Models\NavigationCategory;
use Illuminate\Database\Seeder;

class NavigationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'description' => 'Produk elektronik seperti laptop, smartphone, dan gadget lainnya',
                'is_active' => true,
                'sort_order' => 1,
                'icon' => 'heroicon-o-computer-desktop',
                'color' => '#3B82F6',
            ],
            [
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Pakaian, sepatu, tas, dan aksesoris fashion',
                'is_active' => true,
                'sort_order' => 2,
                'icon' => 'heroicon-o-sparkles',
                'color' => '#EC4899',
            ],
            [
                'name' => 'Makanan & Minuman',
                'slug' => 'makanan-minuman',
                'description' => 'Makanan, minuman, dan kebutuhan dapur',
                'is_active' => true,
                'sort_order' => 3,
                'icon' => 'heroicon-o-cake',
                'color' => '#F59E0B',
            ],
            [
                'name' => 'Kesehatan & Kecantikan',
                'slug' => 'kesehatan-kecantikan',
                'description' => 'Produk kesehatan, kecantikan, dan perawatan tubuh',
                'is_active' => true,
                'sort_order' => 4,
                'icon' => 'heroicon-o-heart',
                'color' => '#EF4444',
            ],
            [
                'name' => 'Rumah Tangga',
                'slug' => 'rumah-tangga',
                'description' => 'Peralatan rumah tangga, furniture, dan kebutuhan sehari-hari',
                'is_active' => true,
                'sort_order' => 5,
                'icon' => 'heroicon-o-home',
                'color' => '#10B981',
            ],
        ];

        foreach ($categories as $category) {
            NavigationCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Navigation categories seeded successfully!');
    }
}