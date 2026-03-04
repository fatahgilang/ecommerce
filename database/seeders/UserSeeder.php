<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin utama
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@tokomakmur.com',
            'password' => Hash::make('password123'),
        ]);

        // Manager toko
        User::create([
            'name' => 'Manager Toko',
            'email' => 'manager@tokomakmur.com',
            'password' => Hash::make('password123'),
        ]);

        // Kasir-kasir
        $kasirData = [
            ['name' => 'Siti Nurhaliza', 'email' => 'siti@tokomakmur.com'],
            ['name' => 'Budi Santoso', 'email' => 'budi@tokomakmur.com'],
            ['name' => 'Rina Wati', 'email' => 'rina@tokomakmur.com'],
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad@tokomakmur.com'],
            ['name' => 'Dewi Sartika', 'email' => 'dewi@tokomakmur.com'],
            ['name' => 'Joko Widodo', 'email' => 'joko@tokomakmur.com'],
        ];

        foreach ($kasirData as $kasir) {
            User::create([
                'name' => $kasir['name'],
                'email' => $kasir['email'],
                'password' => Hash::make('kasir123'),
            ]);
        }

        $this->command->info('Users seeded successfully!');
    }
}