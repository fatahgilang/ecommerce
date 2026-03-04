<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shops = [
            [
                'shop_name' => 'Toko Makmur Jaya',
                'description' => 'Toko serba ada yang menyediakan kebutuhan sehari-hari dengan harga terjangkau dan kualitas terbaik.',
                'address' => 'Jl. Raya Bogor No. 123, Cibinong, Bogor, Jawa Barat 16911',
                'phone' => '021-87654321',
                'email' => 'info@tokomakmur.com',
            ],
            [
                'shop_name' => 'Warung Berkah',
                'description' => 'Warung tradisional yang menjual makanan ringan, minuman, dan kebutuhan pokok.',
                'address' => 'Jl. Pahlawan No. 45, Depok, Jawa Barat 16424',
                'phone' => '021-12345678',
                'email' => 'berkah@warung.com',
            ],
            [
                'shop_name' => 'Minimarket Segar',
                'description' => 'Minimarket modern dengan produk segar dan lengkap untuk kebutuhan keluarga.',
                'address' => 'Jl. Margonda Raya No. 88, Depok, Jawa Barat 16431',
                'phone' => '021-98765432',
                'email' => 'segar@minimarket.com',
            ],
            [
                'shop_name' => 'Toko Elektronik Maju',
                'description' => 'Spesialis elektronik dan gadget dengan garansi resmi dan harga kompetitif.',
                'address' => 'Jl. Sudirman No. 67, Jakarta Pusat, DKI Jakarta 10220',
                'phone' => '021-55667788',
                'email' => 'maju@elektronik.com',
            ],
            [
                'shop_name' => 'Fashion Store Trendy',
                'description' => 'Toko fashion dengan koleksi pakaian trendy untuk pria dan wanita.',
                'address' => 'Jl. Thamrin No. 99, Jakarta Pusat, DKI Jakarta 10310',
                'phone' => '021-33445566',
                'email' => 'trendy@fashion.com',
            ],
        ];

        foreach ($shops as $shop) {
            Shop::create($shop);
        }

        $this->command->info('Shops seeded successfully!');
    }
}