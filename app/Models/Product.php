<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_description',
        'product_price',
        'discount_price',
        'discount_percentage',
        'discount_start_date',
        'discount_end_date',
        'has_discount',
        'unit',
        'stock',
        'image',
        'weight'
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_start_date' => 'date',
        'discount_end_date' => 'date',
        'has_discount' => 'boolean',
    ];

    protected $appends = ['image_url'];

    /**
     * Get the full URL for the product image
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        // If image already has full URL (http/https), return as is
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Otherwise, prepend storage URL
        return asset('storage/' . $this->image);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }



    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'product_discounts');
    }

    /**
     * Check if product has active discount
     */
    public function hasActiveDiscount(): bool
    {
        if (!$this->has_discount) {
            return false;
        }

        $now = now();
        
        return $this->discount_start_date <= $now && 
               $this->discount_end_date >= $now;
    }

    /**
     * Get current price (with discount if applicable)
     */
    public function getCurrentPrice(): float
    {
        if (!$this->hasActiveDiscount()) {
            return $this->product_price;
        }

        if ($this->discount_price) {
            return $this->discount_price;
        }

        if ($this->discount_percentage) {
            return $this->product_price * (1 - ($this->discount_percentage / 100));
        }

        return $this->product_price;
    }

    /**
     * Get discount amount
     */
    public function getDiscountAmount(): float
    {
        if (!$this->hasActiveDiscount()) {
            return 0;
        }

        if ($this->discount_price) {
            return $this->product_price - $this->discount_price;
        }

        if ($this->discount_percentage) {
            return $this->product_price * ($this->discount_percentage / 100);
        }

        return 0;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentage(): float
    {
        if (!$this->hasActiveDiscount()) {
            return 0;
        }

        if ($this->discount_percentage) {
            return $this->discount_percentage;
        }

        if ($this->discount_price) {
            $discountAmount = $this->product_price - $this->discount_price;
            return ($discountAmount / $this->product_price) * 100;
        }

        return 0;
    }

    /**
     * Apply discount to product
     */
    public function applyDiscount(?float $discountPrice = null, ?float $discountPercentage = null, ?string $startDate = null, ?string $endDate = null): void
    {
        $this->update([
            'discount_price' => $discountPrice,
            'discount_percentage' => $discountPercentage,
            'discount_start_date' => $startDate ?: now()->toDateString(),
            'discount_end_date' => $endDate ?: now()->addDays(30)->toDateString(),
            'has_discount' => true,
        ]);
    }

    /**
     * Remove discount from product
     */
    public function removeDiscount(): void
    {
        $this->update([
            'discount_price' => null,
            'discount_percentage' => null,
            'discount_start_date' => null,
            'discount_end_date' => null,
            'has_discount' => false,
        ]);
    }
}