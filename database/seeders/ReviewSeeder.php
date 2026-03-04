<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->command->error('No products found! Please run ProductSeeder first.');
            return;
        }

        $reviewTexts = [
            // Rating 5 (Excellent)
            5 => [
                'Produk sangat bagus! Kualitas sesuai dengan harga. Puas banget!',
                'Pelayanan cepat dan produk berkualitas. Recommended!',
                'Barang sampai dengan selamat dan sesuai ekspektasi. Terima kasih!',
                'Kualitas produk excellent, packaging rapi. Will order again!',
                'Sangat puas dengan pembelian ini. Produk original dan berkualitas.',
                'Pelayanan ramah, pengiriman cepat. Produk sesuai deskripsi.',
                'Top quality! Sudah langganan di toko ini karena selalu puas.',
                'Barang bagus, harga reasonable. Highly recommended!',
            ],
            
            // Rating 4 (Good)
            4 => [
                'Produk bagus, tapi pengiriman agak lama. Overall satisfied.',
                'Kualitas baik, sesuai dengan harga. Cuma packaging bisa lebih rapi.',
                'Barang oke, pelayanan juga baik. Mungkin next time bisa lebih cepat.',
                'Produk sesuai ekspektasi. Pengiriman standar, tidak terlalu cepat.',
                'Good quality, tapi ada sedikit cacat di kemasan. Produk tetap bagus.',
                'Puas dengan produknya. Hanya saja komunikasi bisa diperbaiki.',
                'Barang sampai dengan aman. Kualitas baik untuk harga segini.',
            ],
            
            // Rating 3 (Average)
            3 => [
                'Produk standar, tidak lebih tidak kurang. Sesuai harga.',
                'Barang oke, tapi ada beberapa hal yang bisa diperbaiki.',
                'Kualitas cukup baik. Pengiriman agak lama dari perkiraan.',
                'Produk sesuai deskripsi. Pelayanan bisa lebih responsif.',
                'Barang sampai dengan selamat. Kualitas standar.',
                'Cukup puas, tapi masih ada room for improvement.',
            ],
            
            // Rating 2 (Below Average)
            2 => [
                'Produk kurang sesuai ekspektasi. Kualitas bisa lebih baik.',
                'Barang sampai terlambat dan ada sedikit kerusakan.',
                'Kualitas produk biasa saja. Tidak sesuai dengan harga.',
                'Pelayanan kurang responsif. Produk juga tidak terlalu bagus.',
                'Agak kecewa dengan kualitas produk. Pengiriman juga lama.',
            ],
            
            // Rating 1 (Poor)
            1 => [
                'Sangat kecewa! Produk tidak sesuai dengan foto dan deskripsi.',
                'Barang rusak saat sampai. Pelayanan juga tidak responsif.',
                'Kualitas sangat buruk. Tidak recommended!',
                'Produk cacat dan pengiriman sangat lama. Tidak akan beli lagi.',
            ],
        ];

        $reviewCount = 0;
        
        // Generate reviews for random products
        for ($i = 0; $i < 50; $i++) {
            $product = $products->random();
            
            // Weight untuk rating (lebih banyak rating tinggi)
            $ratingWeights = [1 => 8, 2 => 12, 3 => 20, 4 => 30, 5 => 30];
            $rating = $this->getWeightedRandomRating($ratingWeights);
            $reviewText = $reviewTexts[$rating][array_rand($reviewTexts[$rating])];
            
            // Random verified purchase status
            $isVerifiedPurchase = rand(0, 1);
            
            $reviewDate = today()->subDays(rand(1, 90));
            
            Review::create([
                'product_id' => $product->id,
                'review' => $reviewText,
                'rating' => $rating,
                'is_verified_purchase' => $isVerifiedPurchase,
                'created_at' => $reviewDate,
                'updated_at' => $reviewDate,
            ]);
            
            $reviewCount++;
        }

        $this->command->info("Reviews seeded successfully! Created {$reviewCount} reviews.");
    }

    private function getWeightedRandomRating(array $weights): int
    {
        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        
        $currentWeight = 0;
        foreach ($weights as $rating => $weight) {
            $currentWeight += $weight;
            if ($random <= $currentWeight) {
                return $rating;
            }
        }
        
        return 5; // fallback
    }
}