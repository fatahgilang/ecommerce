<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'shipment_type',
        'payment_method',
        'customers_address',
        'product_price',
        'delivery_cost',
        'discount',
        'final_price',
        'status'
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}