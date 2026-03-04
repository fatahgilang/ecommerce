<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Shop;

class ProductSeeder extends Seeder
{
    /**
     * Generate dummy image URL based on product name
     */
    private function generateDummyImage(string $productName, string $category = 'product'): string
    {
        // Color schemes based on product category
        $colorSchemes = [
            'food' => ['bg' => 'FF6B6B', 'text' => 'FFFFFF'], // Red
            'electronics' => ['bg' => '4ECDC4', 'text' => 'FFFFFF'], // Teal
            'fashion' => ['bg' => 'FFE66D', 'text' => '2C3E50'], // Yellow
            'fresh' => ['bg' => '95E1D3', 'text' => '2C3E50'], // Mint
            'grocery' => ['bg' => 'F38181', 'text' => 'FFFFFF'], // Pink
            'default' => ['bg' => '3498DB', 'text' => 'FFFFFF'], // Blue
        ];

        // Determine category based on product name
        $detectedCategory = 'default';
        if (preg_match('/(nasi|mie|gorengan|kopi|teh|gudeg|kerupuk)/i', $productName)) {
            $detectedCategory = 'food';
        } elseif (preg_match('/(smartphone|laptop|earphone|power bank|speaker|smartwatch|kabel)/i', $productName)) {
            $detectedCategory = 'electronics';
        } elseif (preg_match('/(kaos|kemeja|dress|celana|blouse|jaket|sepatu|tas)/i', $productName)) {
            $detectedCategory = 'fashion';
        } elseif (preg_match('/(ayam|ikan|sayur|tomat|bawang|cabai|pisang|jeruk)/i', $productName)) {
            $detectedCategory = 'fresh';
        } elseif (preg_match('/(beras|minyak|gula|telur|susu|roti|sabun|shampo|pasta|deterjen)/i', $productName)) {
            $detectedCategory = 'grocery';
        }

        $colors = $colorSchemes[$detectedCategory];
        $text = urlencode($productName);
        
        // Using placehold.co service for dummy images
        return "https://placehold.co/600x600/{$colors['bg']}/{$colors['text']}?text={$text}";
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = Shop::all();
        
        if ($shops->isEmpty()) {
            $this->command->error('No shops found! Please run ShopSeeder first.');
            return;
        }

        // Produk untuk Toko Makmur Jaya (Toko Serba Ada)
        $tokoMakmur = $shops->where('shop_name', 'Toko Makmur Jaya')->first();
        if ($tokoMakmur) {
            $productsMakmur = [
                ['name' => 'Beras Premium 5kg', 'desc' => 'Beras premium kualitas terbaik, pulen dan wangi', 'price' => 75000, 'unit' => 'karung', 'stock' => 50, 'weight' => 5.0],
                ['name' => 'Minyak Goreng 2L', 'desc' => 'Minyak goreng berkualitas untuk memasak sehari-hari', 'price' => 32000, 'unit' => 'botol', 'stock' => 80, 'weight' => 2.0],
                ['name' => 'Gula Pasir 1kg', 'desc' => 'Gula pasir putih bersih untuk kebutuhan dapur', 'price' => 15000, 'unit' => 'kg', 'stock' => 100, 'weight' => 1.0],
                ['name' => 'Telur Ayam 1kg', 'desc' => 'Telur ayam segar dari peternakan lokal', 'price' => 28000, 'unit' => 'kg', 'stock' => 60, 'weight' => 1.0],
                ['name' => 'Susu UHT 1L', 'desc' => 'Susu UHT full cream kaya nutrisi', 'price' => 18000, 'unit' => 'kotak', 'stock' => 120, 'weight' => 1.0],
                ['name' => 'Roti Tawar', 'desc' => 'Roti tawar segar untuk sarapan keluarga', 'price' => 12000, 'unit' => 'bungkus', 'stock' => 40, 'weight' => 0.5],
                ['name' => 'Sabun Mandi', 'desc' => 'Sabun mandi dengan formula lembut untuk kulit', 'price' => 8500, 'unit' => 'pcs', 'stock' => 150, 'weight' => 0.1],
                ['name' => 'Shampo 400ml', 'desc' => 'Shampo untuk rambut sehat dan berkilau', 'price' => 25000, 'unit' => 'botol', 'stock' => 75, 'weight' => 0.4],
                ['name' => 'Pasta Gigi', 'desc' => 'Pasta gigi dengan fluoride untuk gigi sehat', 'price' => 12500, 'unit' => 'tube', 'stock' => 90, 'weight' => 0.15],
                ['name' => 'Deterjen 1kg', 'desc' => 'Deterjen bubuk untuk mencuci pakaian', 'price' => 22000, 'unit' => 'kg', 'stock' => 65, 'weight' => 1.0],
            ];

            foreach ($productsMakmur as $product) {
                Product::create([
                    'shop_id' => $tokoMakmur->id,
                    'product_name' => $product['name'],
                    'product_description' => $product['desc'],
                    'product_price' => $product['price'],
                    'price_per_unit' => $product['price'],
                    'unit' => $product['unit'],
                    'stock' => $product['stock'],
                    'weight' => $product['weight'],
                    'image' => $this->generateDummyImage($product['name']),
                ]);
            }
        }

        // Produk untuk Warung Berkah (Makanan & Minuman)
        $warungBerkah = $shops->where('shop_name', 'Warung Berkah')->first();
        if ($warungBerkah) {
            $productsWarung = [
                ['name' => 'Nasi Gudeg', 'desc' => 'Nasi gudeg khas Yogyakarta dengan ayam dan telur', 'price' => 18000, 'unit' => 'porsi', 'stock' => 25, 'weight' => 0.5],
                ['name' => 'Mie Ayam', 'desc' => 'Mie ayam dengan topping ayam dan bakso', 'price' => 15000, 'unit' => 'porsi', 'stock' => 30, 'weight' => 0.4],
                ['name' => 'Es Teh Manis', 'desc' => 'Es teh manis segar untuk menghilangkan dahaga', 'price' => 5000, 'unit' => 'gelas', 'stock' => 50, 'weight' => 0.3],
                ['name' => 'Kopi Hitam', 'desc' => 'Kopi hitam tubruk tradisional', 'price' => 7000, 'unit' => 'gelas', 'stock' => 40, 'weight' => 0.2],
                ['name' => 'Gorengan Mix', 'desc' => 'Aneka gorengan: tahu, tempe, pisang goreng', 'price' => 12000, 'unit' => 'porsi', 'stock' => 35, 'weight' => 0.3],
                ['name' => 'Kerupuk Udang', 'desc' => 'Kerupuk udang renyah dan gurih', 'price' => 8000, 'unit' => 'bungkus', 'stock' => 60, 'weight' => 0.1],
                ['name' => 'Permen Karet', 'desc' => 'Permen karet aneka rasa', 'price' => 2000, 'unit' => 'pcs', 'stock' => 200, 'weight' => 0.01],
                ['name' => 'Rokok Kretek', 'desc' => 'Rokok kretek lokal', 'price' => 25000, 'unit' => 'bungkus', 'stock' => 80, 'weight' => 0.02],
            ];

            foreach ($productsWarung as $product) {
                Product::create([
                    'shop_id' => $warungBerkah->id,
                    'product_name' => $product['name'],
                    'product_description' => $product['desc'],
                    'product_price' => $product['price'],
                    'price_per_unit' => $product['price'],
                    'unit' => $product['unit'],
                    'stock' => $product['stock'],
                    'weight' => $product['weight'],
                    'image' => $this->generateDummyImage($product['name']),
                ]);
            }
        }

        // Produk untuk Minimarket Segar (Produk Segar)
        $minimarketSegar = $shops->where('shop_name', 'Minimarket Segar')->first();
        if ($minimarketSegar) {
            $productsSegar = [
                ['name' => 'Ayam Potong Segar', 'desc' => 'Ayam potong segar dari peternakan lokal', 'price' => 35000, 'unit' => 'kg', 'stock' => 20, 'weight' => 1.0],
                ['name' => 'Ikan Bandeng', 'desc' => 'Ikan bandeng segar dari tambak', 'price' => 28000, 'unit' => 'kg', 'stock' => 15, 'weight' => 1.0],
                ['name' => 'Sayur Kangkung', 'desc' => 'Kangkung segar untuk sayur bening', 'price' => 5000, 'unit' => 'ikat', 'stock' => 40, 'weight' => 0.2],
                ['name' => 'Tomat Segar', 'desc' => 'Tomat merah segar untuk masakan', 'price' => 12000, 'unit' => 'kg', 'stock' => 30, 'weight' => 1.0],
                ['name' => 'Bawang Merah', 'desc' => 'Bawang merah kualitas premium', 'price' => 35000, 'unit' => 'kg', 'stock' => 25, 'weight' => 1.0],
                ['name' => 'Cabai Merah', 'desc' => 'Cabai merah segar dan pedas', 'price' => 45000, 'unit' => 'kg', 'stock' => 20, 'weight' => 1.0],
                ['name' => 'Pisang Cavendish', 'desc' => 'Pisang cavendish manis dan segar', 'price' => 18000, 'unit' => 'kg', 'stock' => 35, 'weight' => 1.0],
                ['name' => 'Jeruk Manis', 'desc' => 'Jeruk manis kaya vitamin C', 'price' => 22000, 'unit' => 'kg', 'stock' => 40, 'weight' => 1.0],
            ];

            foreach ($productsSegar as $product) {
                Product::create([
                    'shop_id' => $minimarketSegar->id,
                    'product_name' => $product['name'],
                    'product_description' => $product['desc'],
                    'product_price' => $product['price'],
                    'price_per_unit' => $product['price'],
                    'unit' => $product['unit'],
                    'stock' => $product['stock'],
                    'weight' => $product['weight'],
                    'image' => $this->generateDummyImage($product['name']),
                ]);
            }
        }

        // Produk untuk Toko Elektronik Maju
        $tokoElektronik = $shops->where('shop_name', 'Toko Elektronik Maju')->first();
        if ($tokoElektronik) {
            $productsElektronik = [
                ['name' => 'Smartphone Android', 'desc' => 'Smartphone Android dengan kamera 48MP dan RAM 6GB', 'price' => 2500000, 'unit' => 'pcs', 'stock' => 15, 'weight' => 0.2],
                ['name' => 'Laptop Gaming', 'desc' => 'Laptop gaming dengan processor Intel i5 dan VGA dedicated', 'price' => 8500000, 'unit' => 'pcs', 'stock' => 8, 'weight' => 2.5],
                ['name' => 'Earphone Bluetooth', 'desc' => 'Earphone bluetooth dengan noise cancelling', 'price' => 350000, 'unit' => 'pcs', 'stock' => 25, 'weight' => 0.1],
                ['name' => 'Power Bank 10000mAh', 'desc' => 'Power bank kapasitas 10000mAh dengan fast charging', 'price' => 180000, 'unit' => 'pcs', 'stock' => 30, 'weight' => 0.3],
                ['name' => 'Kabel USB Type-C', 'desc' => 'Kabel USB Type-C untuk charging dan data transfer', 'price' => 25000, 'unit' => 'pcs', 'stock' => 50, 'weight' => 0.05],
                ['name' => 'Speaker Bluetooth', 'desc' => 'Speaker bluetooth portable dengan bass yang kuat', 'price' => 450000, 'unit' => 'pcs', 'stock' => 20, 'weight' => 0.8],
                ['name' => 'Smartwatch', 'desc' => 'Smartwatch dengan fitur fitness tracking dan notifikasi', 'price' => 1200000, 'unit' => 'pcs', 'stock' => 12, 'weight' => 0.15],
            ];

            foreach ($productsElektronik as $product) {
                Product::create([
                    'shop_id' => $tokoElektronik->id,
                    'product_name' => $product['name'],
                    'product_description' => $product['desc'],
                    'product_price' => $product['price'],
                    'price_per_unit' => $product['price'],
                    'unit' => $product['unit'],
                    'stock' => $product['stock'],
                    'weight' => $product['weight'],
                    'image' => $this->generateDummyImage($product['name']),
                ]);
            }
        }

        // Produk untuk Fashion Store Trendy
        $fashionStore = $shops->where('shop_name', 'Fashion Store Trendy')->first();
        if ($fashionStore) {
            $productsFashion = [
                ['name' => 'Kaos Polos Premium', 'desc' => 'Kaos polos berbahan cotton combed 30s', 'price' => 85000, 'unit' => 'pcs', 'stock' => 40, 'weight' => 0.2],
                ['name' => 'Kemeja Formal Pria', 'desc' => 'Kemeja formal pria untuk kerja dan acara resmi', 'price' => 150000, 'unit' => 'pcs', 'stock' => 25, 'weight' => 0.3],
                ['name' => 'Dress Casual Wanita', 'desc' => 'Dress casual wanita untuk hangout dan jalan-jalan', 'price' => 180000, 'unit' => 'pcs', 'stock' => 20, 'weight' => 0.25],
                ['name' => 'Celana Jeans Pria', 'desc' => 'Celana jeans pria model slim fit', 'price' => 220000, 'unit' => 'pcs', 'stock' => 30, 'weight' => 0.6],
                ['name' => 'Blouse Wanita', 'desc' => 'Blouse wanita elegan untuk kerja', 'price' => 120000, 'unit' => 'pcs', 'stock' => 35, 'weight' => 0.2],
                ['name' => 'Jaket Hoodie', 'desc' => 'Jaket hoodie unisex untuk cuaca dingin', 'price' => 165000, 'unit' => 'pcs', 'stock' => 28, 'weight' => 0.5],
                ['name' => 'Sepatu Sneakers', 'desc' => 'Sepatu sneakers casual untuk pria dan wanita', 'price' => 320000, 'unit' => 'pasang', 'stock' => 22, 'weight' => 0.8],
                ['name' => 'Tas Ransel', 'desc' => 'Tas ransel untuk sekolah dan kuliah', 'price' => 145000, 'unit' => 'pcs', 'stock' => 18, 'weight' => 0.4],
            ];

            foreach ($productsFashion as $product) {
                Product::create([
                    'shop_id' => $fashionStore->id,
                    'product_name' => $product['name'],
                    'product_description' => $product['desc'],
                    'product_price' => $product['price'],
                    'price_per_unit' => $product['price'],
                    'unit' => $product['unit'],
                    'stock' => $product['stock'],
                    'weight' => $product['weight'],
                    'image' => $this->generateDummyImage($product['name']),
                ]);
            }
        }

        $this->command->info('Products seeded successfully!');
    }
}