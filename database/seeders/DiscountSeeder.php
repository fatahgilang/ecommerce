<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use Carbon\Carbon;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $discounts = [
            // Diskon Persentase
            [
                'name' => 'Diskon Hari Ini',
                'code' => 'HARI10',
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 50000,
                'maximum_discount' => 25000,
                'start_date' => today(),
                'end_date' => today()->addDays(7),
                'usage_limit' => 100,
                'used_count' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Weekend',
                'code' => 'WEEKEND15',
                'type' => 'percentage',
                'value' => 15,
                'minimum_amount' => 100000,
                'maximum_discount' => 50000,
                'start_date' => today()->startOfWeek()->addDays(5), // Sabtu
                'end_date' => today()->startOfWeek()->addDays(6), // Minggu
                'usage_limit' => 50,
                'used_count' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Member VIP',
                'code' => 'VIP20',
                'type' => 'percentage',
                'value' => 20,
                'minimum_amount' => 200000,
                'maximum_discount' => 100000,
                'start_date' => today()->subDays(30),
                'end_date' => today()->addDays(365),
                'usage_limit' => 200,
                'used_count' => 45,
                'is_active' => true,
            ],
            [
                'name' => 'Flash Sale',
                'code' => 'FLASH25',
                'type' => 'percentage',
                'value' => 25,
                'minimum_amount' => 150000,
                'maximum_discount' => 75000,
                'start_date' => today()->addHours(2),
                'end_date' => today()->addHours(6),
                'usage_limit' => 30,
                'used_count' => 0,
                'is_active' => true,
            ],

            // Diskon Jumlah Tetap
            [
                'name' => 'Potongan 20 Ribu',
                'code' => 'HEMAT20',
                'type' => 'fixed_amount',
                'value' => 20000,
                'minimum_amount' => 100000,
                'maximum_discount' => null,
                'start_date' => today(),
                'end_date' => today()->addDays(30),
                'usage_limit' => 150,
                'used_count' => 32,
                'is_active' => true,
            ],
            [
                'name' => 'Cashback 50 Ribu',
                'code' => 'CASHBACK50',
                'type' => 'fixed_amount',
                'value' => 50000,
                'minimum_amount' => 300000,
                'maximum_discount' => null,
                'start_date' => today()->subDays(7),
                'end_date' => today()->addDays(23),
                'usage_limit' => 75,
                'used_count' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Pembelian Pertama',
                'code' => 'NEWBIE15K',
                'type' => 'fixed_amount',
                'value' => 15000,
                'minimum_amount' => 75000,
                'maximum_discount' => null,
                'start_date' => today()->subDays(60),
                'end_date' => today()->addDays(305),
                'usage_limit' => 500,
                'used_count' => 89,
                'is_active' => true,
            ],
            [
                'name' => 'Promo Akhir Bulan',
                'code' => 'ENDMONTH30',
                'type' => 'fixed_amount',
                'value' => 30000,
                'minimum_amount' => 200000,
                'maximum_discount' => null,
                'start_date' => today()->endOfMonth()->subDays(2),
                'end_date' => today()->endOfMonth(),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ],

            // Diskon Tanpa Kode (Manual)
            [
                'name' => 'Diskon Member Reguler',
                'code' => null,
                'type' => 'percentage',
                'value' => 5,
                'minimum_amount' => 0,
                'maximum_discount' => 15000,
                'start_date' => today()->subDays(90),
                'end_date' => today()->addDays(275),
                'usage_limit' => null,
                'used_count' => 156,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Senior Citizen',
                'code' => null,
                'type' => 'percentage',
                'value' => 10,
                'minimum_amount' => 25000,
                'maximum_discount' => 20000,
                'start_date' => today()->subDays(365),
                'end_date' => today()->addDays(365),
                'usage_limit' => null,
                'used_count' => 78,
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Karyawan',
                'code' => null,
                'type' => 'percentage',
                'value' => 15,
                'minimum_amount' => 0,
                'maximum_discount' => 50000,
                'start_date' => today()->subDays(180),
                'end_date' => today()->addDays(185),
                'usage_limit' => null,
                'used_count' => 23,
                'is_active' => true,
            ],

            // Diskon Tidak Aktif (Expired)
            [
                'name' => 'Diskon Tahun Baru (Expired)',
                'code' => 'NEWYEAR2024',
                'type' => 'percentage',
                'value' => 30,
                'minimum_amount' => 100000,
                'maximum_discount' => 100000,
                'start_date' => Carbon::create(2024, 12, 31),
                'end_date' => Carbon::create(2025, 1, 7),
                'usage_limit' => 200,
                'used_count' => 187,
                'is_active' => false,
            ],
            [
                'name' => 'Promo Valentine (Expired)',
                'code' => 'LOVE50K',
                'type' => 'fixed_amount',
                'value' => 50000,
                'minimum_amount' => 250000,
                'maximum_discount' => null,
                'start_date' => Carbon::create(2025, 2, 10),
                'end_date' => Carbon::create(2025, 2, 16),
                'usage_limit' => 100,
                'used_count' => 67,
                'is_active' => false,
            ],
        ];

        foreach ($discounts as $discount) {
            Discount::create($discount);
        }

        $this->command->info('Discounts seeded successfully!');
    }
}