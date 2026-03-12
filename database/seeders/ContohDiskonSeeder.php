<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ContohDiskonSeeder extends Seeder
{
    /**
     * Contoh implementasi berbagai jenis diskon
     */
    public function run(): void
    {
        $this->command->info('Menerapkan contoh diskon pada produk...');

        // 1. Flash Sale - Diskon 30% untuk elektronik
        $smartphone = Product::where('product_name', 'LIKE', '%Power Bank%')->first();
        if ($smartphone) {
            $smartphone->applyDiscount(
                discountPercentage: 30,
                startDate: now()->toDateString(),
                endDate: now()->addHours(24)->toDateString()
            );
            $this->command->info("✅ Flash Sale 30% diterapkan pada: {$smartphone->product_name}");
        }

        // 2. Promo Mingguan - Harga khusus untuk beras
        $beras = Product::where('product_name', 'LIKE', '%Beras%')->first();
        if ($beras) {
            $originalPrice = $beras->product_price;
            $discountPrice = $originalPrice * 0.87; // 13% off
            
            $beras->applyDiscount(
                discountPrice: $discountPrice,
                startDate: now()->toDateString(),
                endDate: now()->addDays(7)->toDateString()
            );
            $this->command->info("✅ Promo mingguan diterapkan pada: {$beras->product_name}");
            $this->command->info("   Harga asli: Rp " . number_format($originalPrice, 0, ',', '.'));
            $this->command->info("   Harga diskon: Rp " . number_format($discountPrice, 0, ',', '.'));
        }

        // 3. Clearance Sale - Diskon 50% untuk produk tertentu
        $sayuran = Product::where('product_name', 'LIKE', '%Kangkung%')->first();
        if ($sayuran) {
            $sayuran->applyDiscount(
                discountPercentage: 50,
                startDate: now()->toDateString(),
                endDate: now()->addDays(3)->toDateString()
            );
            $this->command->info("✅ Clearance Sale 50% diterapkan pada: {$sayuran->product_name}");
        }

        // 4. Promo Akhir Pekan - Diskon 15% untuk bumbu dapur
        $cabai = Product::where('product_name', 'LIKE', '%Cabai%')->first();
        if ($cabai) {
            $cabai->applyDiscount(
                discountPercentage: 15,
                startDate: now()->startOfWeek()->addDays(5)->toDateString(), // Sabtu
                endDate: now()->startOfWeek()->addDays(6)->toDateString()    // Minggu
            );
            $this->command->info("✅ Promo weekend 15% diterapkan pada: {$cabai->product_name}");
        }

        // 5. Promo Bulk - Harga khusus untuk pembelian dalam jumlah besar
        $telur = Product::where('product_name', 'LIKE', '%Telur%')->first();
        if ($telur) {
            $originalPrice = $telur->product_price;
            $bulkPrice = $originalPrice * 0.85; // 15% off untuk bulk
            
            $telur->applyDiscount(
                discountPrice: $bulkPrice,
                startDate: now()->toDateString(),
                endDate: now()->addDays(14)->toDateString()
            );
            $this->command->info("✅ Promo bulk 15% diterapkan pada: {$telur->product_name}");
        }

        // 6. Diskon Terbatas - Hanya untuk hari ini
        $minyak = Product::where('product_name', 'LIKE', '%Minyak%')->first();
        if ($minyak) {
            $minyak->applyDiscount(
                discountPercentage: 25,
                startDate: now()->toDateString(),
                endDate: now()->toDateString() // Hanya hari ini
            );
            $this->command->info("✅ Diskon terbatas 25% (hari ini saja) diterapkan pada: {$minyak->product_name}");
        }

        $this->command->info('');
        $this->command->info('🎉 Semua contoh diskon berhasil diterapkan!');
        $this->command->info('');
        $this->command->info('Cara cek hasil:');
        $this->command->info('1. Buka admin panel: /admin');
        $this->command->info('2. Masuk ke menu Produk');
        $this->command->info('3. Lihat kolom "Diskon", "Harga Saat Ini", dan "Diskon (%)"');
        $this->command->info('4. Buka website utama untuk melihat tampilan diskon di frontend');
        $this->command->info('');
        
        // Tampilkan ringkasan
        $activeDiscounts = Product::where('has_discount', true)
            ->where('discount_start_date', '<=', now())
            ->where('discount_end_date', '>=', now())
            ->count();
            
        $this->command->info("📊 Total produk dengan diskon aktif: {$activeDiscounts}");
    }
}