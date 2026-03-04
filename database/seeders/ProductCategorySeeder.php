<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus kategori yang sudah ada
        ProductCategory::truncate();

        // Ambil semua produk
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('Tidak ada produk! Jalankan ProductSeeder terlebih dahulu.');
            return;
        }

        // Mapping kategori berdasarkan nama produk
        $categoryMapping = [
            // Elektronik
            'Laptop' => ['Elektronik', 'Komputer', 'Gaming'],
            'Smartphone' => ['Elektronik', 'Gadget', 'Komunikasi'],
            'Tablet' => ['Elektronik', 'Gadget', 'Portable'],
            'Headphone' => ['Elektronik', 'Audio', 'Aksesoris'],
            'Speaker' => ['Elektronik', 'Audio', 'Entertainment'],
            'Smartwatch' => ['Elektronik', 'Wearable', 'Fitness'],
            'Camera' => ['Elektronik', 'Fotografi', 'Multimedia'],
            'TV' => ['Elektronik', 'Entertainment', 'Home Appliance'],
            'Monitor' => ['Elektronik', 'Komputer', 'Display'],
            'Keyboard' => ['Elektronik', 'Komputer', 'Aksesoris'],
            'Mouse' => ['Elektronik', 'Komputer', 'Aksesoris'],
            'Printer' => ['Elektronik', 'Komputer', 'Office'],
            
            // Fashion
            'Kaos' => ['Fashion', 'Pakaian', 'Casual'],
            'Kemeja' => ['Fashion', 'Pakaian', 'Formal'],
            'Celana' => ['Fashion', 'Pakaian', 'Bottom'],
            'Jaket' => ['Fashion', 'Pakaian', 'Outerwear'],
            'Sepatu' => ['Fashion', 'Footwear', 'Aksesoris'],
            'Sandal' => ['Fashion', 'Footwear', 'Casual'],
            'Tas' => ['Fashion', 'Aksesoris', 'Bag'],
            'Dompet' => ['Fashion', 'Aksesoris', 'Wallet'],
            'Jam Tangan' => ['Fashion', 'Aksesoris', 'Timepiece'],
            'Kacamata' => ['Fashion', 'Aksesoris', 'Eyewear'],
            
            // Makanan & Minuman
            'Kopi' => ['Makanan & Minuman', 'Minuman', 'Beverage'],
            'Teh' => ['Makanan & Minuman', 'Minuman', 'Beverage'],
            'Snack' => ['Makanan & Minuman', 'Makanan', 'Cemilan'],
            'Coklat' => ['Makanan & Minuman', 'Makanan', 'Dessert'],
            'Biskuit' => ['Makanan & Minuman', 'Makanan', 'Cemilan'],
            'Mie' => ['Makanan & Minuman', 'Makanan', 'Instant'],
            'Susu' => ['Makanan & Minuman', 'Minuman', 'Dairy'],
            'Roti' => ['Makanan & Minuman', 'Makanan', 'Bakery'],
            
            // Olahraga
            'Bola' => ['Olahraga', 'Peralatan', 'Sport Equipment'],
            'Raket' => ['Olahraga', 'Peralatan', 'Sport Equipment'],
            'Matras' => ['Olahraga', 'Fitness', 'Yoga'],
            'Dumbbell' => ['Olahraga', 'Fitness', 'Gym'],
            'Treadmill' => ['Olahraga', 'Fitness', 'Cardio'],
            'Sepeda' => ['Olahraga', 'Outdoor', 'Cycling'],
            
            // Kesehatan & Kecantikan
            'Vitamin' => ['Kesehatan', 'Suplemen', 'Wellness'],
            'Masker' => ['Kesehatan', 'Kecantikan', 'Skincare'],
            'Sabun' => ['Kesehatan', 'Kecantikan', 'Personal Care'],
            'Shampoo' => ['Kesehatan', 'Kecantikan', 'Hair Care'],
            'Parfum' => ['Kesehatan', 'Kecantikan', 'Fragrance'],
            'Lotion' => ['Kesehatan', 'Kecantikan', 'Skincare'],
            
            // Rumah Tangga
            'Piring' => ['Rumah Tangga', 'Peralatan Makan', 'Kitchen'],
            'Gelas' => ['Rumah Tangga', 'Peralatan Makan', 'Kitchen'],
            'Sendok' => ['Rumah Tangga', 'Peralatan Makan', 'Cutlery'],
            'Panci' => ['Rumah Tangga', 'Peralatan Masak', 'Cookware'],
            'Wajan' => ['Rumah Tangga', 'Peralatan Masak', 'Cookware'],
            'Blender' => ['Rumah Tangga', 'Elektronik', 'Kitchen Appliance'],
            'Rice Cooker' => ['Rumah Tangga', 'Elektronik', 'Kitchen Appliance'],
            'Dispenser' => ['Rumah Tangga', 'Elektronik', 'Kitchen Appliance'],
        ];

        $categoriesCreated = 0;

        foreach ($products as $product) {
            $productName = $product->product_name;
            
            // Cari kategori yang cocok berdasarkan keyword di nama produk
            $matchedCategories = [];
            
            foreach ($categoryMapping as $keyword => $categories) {
                if (stripos($productName, $keyword) !== false) {
                    $matchedCategories = $categories;
                    break;
                }
            }
            
            // Jika tidak ada yang cocok, gunakan kategori default berdasarkan toko
            if (empty($matchedCategories)) {
                $shopName = $product->shop->shop_name ?? '';
                
                if (stripos($shopName, 'Elektronik') !== false) {
                    $matchedCategories = ['Elektronik', 'Gadget', 'Technology'];
                } elseif (stripos($shopName, 'Fashion') !== false) {
                    $matchedCategories = ['Fashion', 'Pakaian', 'Style'];
                } elseif (stripos($shopName, 'Makanan') !== false || stripos($shopName, 'Food') !== false) {
                    $matchedCategories = ['Makanan & Minuman', 'Food', 'Beverage'];
                } elseif (stripos($shopName, 'Olahraga') !== false || stripos($shopName, 'Sport') !== false) {
                    $matchedCategories = ['Olahraga', 'Sport', 'Fitness'];
                } else {
                    $matchedCategories = ['Umum', 'Produk', 'General'];
                }
            }
            
            // Buat kategori untuk produk ini
            foreach ($matchedCategories as $categoryName) {
                ProductCategory::create([
                    'product_id' => $product->id,
                    'category_name' => $categoryName,
                    'product_description' => $product->product_description,
                    'unit' => $product->unit,
                    'price_per_unit' => $product->price_per_unit,
                ]);
                
                $categoriesCreated++;
            }
        }

        $this->command->info("Product categories seeded successfully! Created {$categoriesCreated} categories for {$products->count()} products.");
    }
}
