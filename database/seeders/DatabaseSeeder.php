<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        
        // Seed in correct order due to foreign key constraints
        $this->call([
            UserSeeder::class,           // Users (Admin & Kasir)
            ProductSeeder::class,        // Products
            ProductCategorySeeder::class, // Product Categories (depends on Products)
            NavigationCategorySeeder::class, // Navigation Categories (for frontend)
            DiscountSeeder::class,       // Discounts/Promos
            CashRegisterSeeder::class,   // Cash Registers (depends on Users)
            TransactionSeeder::class,    // POS Transactions (depends on CashRegister, Product, User)
            OrderSeeder::class,          // Online Orders (depends on Product)
        ]);
        
        $this->command->info('🎉 Database seeding completed successfully!');
        $this->command->info('');
        $this->command->info('📊 Summary:');
        $this->command->info('- Users: Admin, Manager, and 6 Cashiers');
        $this->command->info('- Products: 39 products with discount support');
        $this->command->info('- Product Categories: Multiple categories per product');
        $this->command->info('- Navigation Categories: 5 main categories for frontend');
        $this->command->info('- Discounts: 14 various discount codes and promos');
        $this->command->info('- Cash Registers: 8 register sessions (2 active, 6 closed)');
        $this->command->info('- Transactions: Multiple POS transactions per register');
        $this->command->info('- Orders: 30 days of store orders (no shipping/reviews)');
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('Admin: admin@tokomakmur.com / password123');
        $this->command->info('Manager: manager@tokomakmur.com / password123');
        $this->command->info('Kasir: [nama]@tokomakmur.com / kasir123');
        $this->command->info('');
        $this->command->info('🌐 Access URLs:');
        $this->command->info('Admin Panel: http://localhost:8000/admin');
        $this->command->info('API Base: http://localhost:8000/api/v1');
    }
}