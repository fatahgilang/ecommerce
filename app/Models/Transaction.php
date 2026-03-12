<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
        'transaction_number',
        'cash_register_id',
        'user_id',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'payment_method',
        'paid_amount',
        'change_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($transaction) {
            if (empty($transaction->transaction_number)) {
                $transaction->transaction_number = self::generateTransactionNumber();
            }
        });
    }

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function paymentSplits(): HasMany
    {
        return $this->hasMany(PaymentSplit::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $lastTransaction = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();
        
        $sequence = $lastTransaction ? 
            intval(substr($lastTransaction->transaction_number, -4)) + 1 : 1;
        
        // Ensure uniqueness by checking if number already exists
        do {
            $transactionNumber = 'TRX-' . $date . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            $exists = self::where('transaction_number', $transactionNumber)->exists();
            if ($exists) {
                $sequence++;
            }
        } while ($exists);
        
        return $transactionNumber;
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->total_amount = $this->subtotal + $this->tax_amount - $this->discount_amount;
        $this->change_amount = max(0, $this->paid_amount - $this->total_amount);
        $this->save();
    }

    public function addItem(Product $product, int $quantity, float $discount = 0): TransactionItem
    {
        $unitPrice = $product->product_price;
        $totalPrice = ($unitPrice * $quantity) - $discount;

        return $this->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_amount' => $discount,
            'total_price' => $totalPrice,
        ]);
    }

    public function canBeRefunded(): bool
    {
        return $this->status === 'completed' && 
               $this->created_at->diffInDays(now()) <= 7;
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }
}