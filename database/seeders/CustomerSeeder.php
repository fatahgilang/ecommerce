<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '081234567890',
                'address' => 'Jl. Merdeka No. 123, Surabaya',
                'date_of_birth' => '1990-05-15',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '081234567891',
                'address' => 'Jl. Pahlawan No. 456, Jakarta',
                'date_of_birth' => '1992-08-20',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'phone' => '081234567892',
                'address' => 'Jl. Diponegoro No. 789, Bandung',
                'date_of_birth' => '1988-03-10',
            ],
            [
                'name' => 'Alice Williams',
                'email' => 'alice@example.com',
                'phone' => '081234567893',
                'address' => 'Jl. Sudirman No. 321, Malang',
                'date_of_birth' => '1995-12-25',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'phone' => '081234567894',
                'address' => 'Jl. Gatot Subroto No. 654, Yogyakarta',
                'date_of_birth' => '1993-07-18',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
}
}