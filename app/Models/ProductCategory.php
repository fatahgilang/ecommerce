<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategory extends Model
{
    protected $fillable = [
        'product_id',
        'category_name',
        'product_description',
        'unit',
        'price_per_unit'
    ];

    protected $casts = [
        'price_per_unit' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}