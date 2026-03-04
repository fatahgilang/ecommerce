<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    protected $fillable = [
        'register_name',
        'user_id',
        'opening_balance',
        'closing_balance',
        'total_sales',
        'total_cash',
        'total_card',
        'total_ewallet',
        'status',
        'opened_at',
        'closed_at',
        'notes'
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_cash' => 'decimal:2',
        'total_card' => 'decimal:2',
        'total_ewallet' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function close(array $data = []): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closing_balance' => $data['closing_balance'] ?? $this->calculateClosingBalance(),
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function calculateClosingBalance(): float
    {
        return $this->opening_balance + $this->total_cash;
    }

    public function updateTotals(): void
    {
        $transactions = $this->transactions()->where('status', 'completed');
        
        $this->update([
            'total_sales' => $transactions->sum('total_amount'),
            'total_cash' => $transactions->where('payment_method', 'cash')->sum('total_amount'),
            'total_card' => $transactions->where('payment_method', 'card')->sum('total_amount'),
            'total_ewallet' => $transactions->where('payment_method', 'ewallet')->sum('total_amount'),
        ]);
    }
}