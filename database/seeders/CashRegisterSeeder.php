<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashRegister;
use App\Models\User;
use Carbon\Carbon;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('name', [
            'Siti Nurhaliza', 'Budi Santoso', 'Rina Wati', 
            'Ahmad Fauzi', 'Dewi Sartika', 'Joko Widodo'
        ])->get();

        if ($users->isEmpty()) {
            $this->command->error('No cashier users found! Please run UserSeeder first.');
            return;
        }

        $cashRegisters = [
            // Kasir yang sedang buka (aktif)
            [
                'register_name' => 'Kasir Utama',
                'user_id' => $users->where('name', 'Siti Nurhaliza')->first()->id,
                'opening_balance' => 500000,
                'total_sales' => 1250000,
                'total_cash' => 850000,
                'total_card' => 300000,
                'total_ewallet' => 100000,
                'status' => 'open',
                'opened_at' => today()->setTime(8, 0),
                'closed_at' => null,
                'notes' => 'Shift pagi - kasir utama',
            ],
            [
                'register_name' => 'Kasir 2',
                'user_id' => $users->where('name', 'Budi Santoso')->first()->id,
                'opening_balance' => 300000,
                'total_sales' => 890000,
                'total_cash' => 520000,
                'total_card' => 250000,
                'total_ewallet' => 120000,
                'status' => 'open',
                'opened_at' => today()->setTime(9, 30),
                'closed_at' => null,
                'notes' => 'Shift pagi - kasir kedua',
            ],

            // Kasir yang sudah ditutup (hari ini)
            [
                'register_name' => 'Kasir Malam',
                'user_id' => $users->where('name', 'Rina Wati')->first()->id,
                'opening_balance' => 400000,
                'closing_balance' => 720000,
                'total_sales' => 680000,
                'total_cash' => 420000,
                'total_card' => 180000,
                'total_ewallet' => 80000,
                'status' => 'closed',
                'opened_at' => now()->subDay()->setTime(18, 0),
                'closed_at' => now()->subDay()->setTime(23, 30),
                'notes' => 'Shift malam kemarin - tutup normal',
            ],
            [
                'register_name' => 'Kasir Weekend',
                'user_id' => $users->where('name', 'Ahmad Fauzi')->first()->id,
                'opening_balance' => 350000,
                'closing_balance' => 890000,
                'total_sales' => 1120000,
                'total_cash' => 670000,
                'total_card' => 320000,
                'total_ewallet' => 130000,
                'status' => 'closed',
                'opened_at' => today()->subDays(2)->setTime(10, 0),
                'closed_at' => today()->subDays(2)->setTime(22, 0),
                'notes' => 'Shift weekend - penjualan bagus',
            ],

            // Kasir minggu lalu
            [
                'register_name' => 'Kasir Siang',
                'user_id' => $users->where('name', 'Dewi Sartika')->first()->id,
                'opening_balance' => 450000,
                'closing_balance' => 785000,
                'total_sales' => 920000,
                'total_cash' => 580000,
                'total_card' => 240000,
                'total_ewallet' => 100000,
                'status' => 'closed',
                'opened_at' => today()->subDays(7)->setTime(12, 0),
                'closed_at' => today()->subDays(7)->setTime(20, 0),
                'notes' => 'Shift siang minggu lalu',
            ],
            [
                'register_name' => 'Kasir Express',
                'user_id' => $users->where('name', 'Joko Widodo')->first()->id,
                'opening_balance' => 200000,
                'closing_balance' => 456000,
                'total_sales' => 540000,
                'total_cash' => 320000,
                'total_card' => 150000,
                'total_ewallet' => 70000,
                'status' => 'closed',
                'opened_at' => today()->subDays(5)->setTime(14, 0),
                'closed_at' => today()->subDays(5)->setTime(18, 0),
                'notes' => 'Shift sore - kasir express',
            ],

            // Kasir bulan lalu
            [
                'register_name' => 'Kasir Promo',
                'user_id' => $users->where('name', 'Siti Nurhaliza')->first()->id,
                'opening_balance' => 600000,
                'closing_balance' => 1240000,
                'total_sales' => 1850000,
                'total_cash' => 1100000,
                'total_card' => 500000,
                'total_ewallet' => 250000,
                'status' => 'closed',
                'opened_at' => today()->subDays(30)->setTime(8, 0),
                'closed_at' => today()->subDays(30)->setTime(21, 0),
                'notes' => 'Hari promo besar - penjualan tertinggi',
            ],
            [
                'register_name' => 'Kasir Backup',
                'user_id' => $users->where('name', 'Budi Santoso')->first()->id,
                'opening_balance' => 250000,
                'closing_balance' => 445000,
                'total_sales' => 380000,
                'total_cash' => 230000,
                'total_card' => 100000,
                'total_ewallet' => 50000,
                'status' => 'closed',
                'opened_at' => today()->subDays(15)->setTime(16, 0),
                'closed_at' => today()->subDays(15)->setTime(20, 0),
                'notes' => 'Kasir backup sore hari',
            ],
        ];

        foreach ($cashRegisters as $register) {
            CashRegister::create($register);
        }

        $this->command->info('Cash Registers seeded successfully!');
    }
}