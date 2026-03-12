<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 5 produk per kategori untuk total 25 produk
        $products = [
            // Produk Kebutuhan Sehari-hari (5 produk)
            ['name' => 'Beras Premium 5kg', 'desc' => 'Beras premium kualitas terbaik, pulen dan wangi', 'price' => 75000, 'unit' => 'karung', 'stock' => 50, 'weight' => 5.0],
            ['name' => 'Minyak Goreng 2L', 'desc' => 'Minyak goreng berkualitas untuk memasak sehari-hari', 'price' => 32000, 'unit' => 'botol', 'stock' => 80, 'weight' => 2.0],
            ['name' => 'Gula Pasir 1kg', 'desc' => 'Gula pasir putih bersih untuk kebutuhan dapur', 'price' => 15000, 'unit' => 'kg', 'stock' => 100, 'weight' => 1.0],
            ['name' => 'Telur Ayam 1kg', 'desc' => 'Telur ayam segar dari peternakan lokal', 'price' => 28000, 'unit' => 'kg', 'stock' => 60, 'weight' => 1.0],
            ['name' => 'Susu UHT 1L', 'desc' => 'Susu UHT full cream kaya nutrisi', 'price' => 18000, 'unit' => 'kotak', 'stock' => 120, 'weight' => 1.0],
            
            // Produk Makanan & Minuman (5 produk)
            ['name' => 'Nasi Gudeg', 'desc' => 'Nasi gudeg khas Yogyakarta dengan ayam dan telur', 'price' => 18000, 'unit' => 'porsi', 'stock' => 25, 'weight' => 0.5],
            ['name' => 'Mie Ayam', 'desc' => 'Mie ayam dengan topping ayam dan bakso', 'price' => 15000, 'unit' => 'porsi', 'stock' => 30, 'weight' => 0.4],
            ['name' => 'Es Teh Manis', 'desc' => 'Es teh manis segar untuk menghilangkan dahaga', 'price' => 5000, 'unit' => 'gelas', 'stock' => 50, 'weight' => 0.3],
            ['name' => 'Kopi Hitam', 'desc' => 'Kopi hitam tubruk tradisional', 'price' => 7000, 'unit' => 'gelas', 'stock' => 40, 'weight' => 0.2],
            ['name' => 'Gorengan Mix', 'desc' => 'Aneka gorengan: tahu, tempe, pisang goreng', 'price' => 12000, 'unit' => 'porsi', 'stock' => 35, 'weight' => 0.3],
            
            // Produk Segar (5 produk)
            ['name' => 'Ayam Potong Segar', 'desc' => 'Ayam potong segar dari peternakan lokal', 'price' => 35000, 'unit' => 'kg', 'stock' => 20, 'weight' => 1.0],
            ['name' => 'Ikan Bandeng', 'desc' => 'Ikan bandeng segar dari tambak', 'price' => 28000, 'unit' => 'kg', 'stock' => 15, 'weight' => 1.0],
            ['name' => 'Sayur Kangkung', 'desc' => 'Kangkung segar untuk sayur bening', 'price' => 5000, 'unit' => 'ikat', 'stock' => 40, 'weight' => 0.2],
            ['name' => 'Tomat Segar', 'desc' => 'Tomat merah segar untuk masakan', 'price' => 12000, 'unit' => 'kg', 'stock' => 30, 'weight' => 1.0],
            ['name' => 'Bawang Merah', 'desc' => 'Bawang merah kualitas premium', 'price' => 35000, 'unit' => 'kg', 'stock' => 25, 'weight' => 1.0],
            
            // Produk Elektronik (5 produk)
            ['name' => 'Smartphone Android', 'desc' => 'Smartphone Android dengan kamera 48MP dan RAM 6GB', 'price' => 2500000, 'unit' => 'pcs', 'stock' => 15, 'weight' => 0.2],
            ['name' => 'Laptop Gaming', 'desc' => 'Laptop gaming dengan processor Intel i5 dan VGA dedicated', 'price' => 8500000, 'unit' => 'pcs', 'stock' => 8, 'weight' => 2.5],
            ['name' => 'Earphone Bluetooth', 'desc' => 'Earphone bluetooth dengan noise cancelling', 'price' => 350000, 'unit' => 'pcs', 'stock' => 25, 'weight' => 0.1],
            ['name' => 'Power Bank 10000mAh', 'desc' => 'Power bank kapasitas 10000mAh dengan fast charging', 'price' => 180000, 'unit' => 'pcs', 'stock' => 30, 'weight' => 0.3],
            ['name' => 'Speaker Bluetooth', 'desc' => 'Speaker bluetooth portable dengan bass yang kuat', 'price' => 450000, 'unit' => 'pcs', 'stock' => 20, 'weight' => 0.8],
            
            // Produk Fashion (5 produk)
            ['name' => 'Kaos Polos Premium', 'desc' => 'Kaos polos berbahan cotton combed 30s', 'price' => 85000, 'unit' => 'pcs', 'stock' => 40, 'weight' => 0.2],
            ['name' => 'Kemeja Formal Pria', 'desc' => 'Kemeja formal pria untuk kerja dan acara resmi', 'price' => 150000, 'unit' => 'pcs', 'stock' => 25, 'weight' => 0.3],
            ['name' => 'Celana Jeans Pria', 'desc' => 'Celana jeans pria model slim fit', 'price' => 220000, 'unit' => 'pcs', 'stock' => 30, 'weight' => 0.6],
            ['name' => 'Jaket Hoodie', 'desc' => 'Jaket hoodie unisex untuk cuaca dingin', 'price' => 165000, 'unit' => 'pcs', 'stock' => 28, 'weight' => 0.5],
            ['name' => 'Sepatu Sneakers', 'desc' => 'Sepatu sneakers casual untuk pria dan wanita', 'price' => 320000, 'unit' => 'pasang', 'stock' => 22, 'weight' => 0.8],
        ];

        foreach ($products as $product) {
            Product::create([
                'product_name' => $product['name'],
                'product_description' => $product['desc'],
                'product_price' => $product['price'],
                'unit' => $product['unit'],
                'stock' => $product['stock'],
                'weight' => $product['weight'],
                'image' => null, // No dummy images, admin can upload real product images
            ]);
        }

        $this->command->info('25 products seeded successfully (5 per category)!');
    }
}