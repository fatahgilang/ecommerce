<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Console\Command;

class FixTransactionPaymentMethods extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:transaction-payment-methods {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix transaction payment methods to match their related orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }
        
        $this->info('🔧 Fixing transaction payment methods...');
        
        // Payment method mapping from order to transaction
        $paymentMethodMap = [
            'cash' => 'cash',
            'bca' => 'transfer',
            'mandiri' => 'transfer', 
            'bri' => 'transfer',
            'gopay' => 'ewallet',
            'ovo' => 'ewallet',
            'dana' => 'ewallet',
        ];
        
        // Get transactions that have related orders
        $transactions = Transaction::whereHas('orders')->with('orders')->get();
        
        $fixedCount = 0;
        $totalCount = $transactions->count();
        
        $this->info("📊 Found {$totalCount} transactions with related orders");
        
        foreach ($transactions as $transaction) {
            $order = $transaction->orders->first();
            
            if (!$order) {
                continue;
            }
            
            // Check if order payment method exists in our map
            if (!array_key_exists($order->payment_method, $paymentMethodMap)) {
                $this->warn("⚠️  Unknown payment method '{$order->payment_method}' in order #{$order->id}");
                continue;
            }
            
            $correctPaymentMethod = $paymentMethodMap[$order->payment_method];
            
            if ($transaction->payment_method !== $correctPaymentMethod) {
                $this->line("🔄 Transaction #{$transaction->id}:");
                $this->line("   Order Payment Method: {$order->payment_method}");
                $this->line("   Current Transaction Method: {$transaction->payment_method}");
                $this->line("   Correct Transaction Method: {$correctPaymentMethod}");
                
                if (!$isDryRun) {
                    $oldNotes = $transaction->notes ?? '';
                    $newNotes = $oldNotes . " [Payment method corrected from {$transaction->payment_method} to {$correctPaymentMethod}]";
                    
                    $transaction->update([
                        'payment_method' => $correctPaymentMethod,
                        'notes' => $newNotes
                    ]);
                    $this->info("   ✅ Fixed!");
                } else {
                    $this->info("   📝 Would be fixed in real run");
                }
                
                $fixedCount++;
            }
        }
        
        if ($fixedCount === 0) {
            $this->info('✅ All transaction payment methods are already correct!');
        } else {
            if ($isDryRun) {
                $this->info("📝 {$fixedCount} transactions would be fixed");
                $this->info("💡 Run without --dry-run to apply changes");
            } else {
                $this->info("✅ Fixed {$fixedCount} transaction payment methods");
            }
        }
        
        // Show summary of current payment methods
        $this->info("\n📈 Current Payment Method Summary:");
        $paymentSummary = Transaction::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();
            
        foreach ($paymentSummary as $summary) {
            $this->line("   {$summary->payment_method}: {$summary->count} transactions");
        }
        
        return 0;
    }
}