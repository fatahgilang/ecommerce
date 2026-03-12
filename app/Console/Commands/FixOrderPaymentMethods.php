<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class FixOrderPaymentMethods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:order-payment-methods {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix order payment methods to use valid values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }
        
        $this->info('🔧 Fixing order payment methods...');
        
        // Valid payment methods for orders
        $validPaymentMethods = ['cash', 'bca', 'mandiri', 'bri', 'gopay', 'ovo', 'dana'];
        
        // Payment method corrections for common invalid values
        $paymentMethodCorrections = [
            'transfer' => 'bca',      // Default transfer to BCA
            'ewallet' => 'gopay',     // Default e-wallet to GoPay
            'card' => 'bca',          // Default card to BCA transfer
            'credit_card' => 'bca',   // Credit card to BCA transfer
            'cod' => 'cash',          // Cash on delivery to cash
        ];
        
        // Get orders with invalid payment methods
        $orders = Order::whereNotIn('payment_method', $validPaymentMethods)->get();
        
        $fixedCount = 0;
        $totalCount = $orders->count();
        
        $this->info("📊 Found {$totalCount} orders with invalid payment methods");
        
        foreach ($orders as $order) {
            $oldPaymentMethod = $order->payment_method;
            $newPaymentMethod = $paymentMethodCorrections[$oldPaymentMethod] ?? 'cash';
            
            $this->line("🔄 Order #{$order->id}:");
            $this->line("   Current Payment Method: {$oldPaymentMethod}");
            $this->line("   Corrected Payment Method: {$newPaymentMethod}");
            
            if (!$isDryRun) {
                $order->update([
                    'payment_method' => $newPaymentMethod
                ]);
                $this->info("   ✅ Fixed!");
            } else {
                $this->info("   📝 Would be fixed in real run");
            }
            
            $fixedCount++;
        }
        
        if ($fixedCount === 0) {
            $this->info('✅ All order payment methods are already valid!');
        } else {
            if ($isDryRun) {
                $this->info("📝 {$fixedCount} orders would be fixed");
                $this->info("💡 Run without --dry-run to apply changes");
            } else {
                $this->info("✅ Fixed {$fixedCount} order payment methods");
            }
        }
        
        // Show summary of current payment methods
        $this->info("\n📈 Current Order Payment Method Summary:");
        $paymentSummary = Order::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();
            
        foreach ($paymentSummary as $summary) {
            $this->line("   {$summary->payment_method}: {$summary->count} orders");
        }
        
        return 0;
    }
}