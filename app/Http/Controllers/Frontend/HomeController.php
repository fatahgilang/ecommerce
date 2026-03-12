<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('stock', '>', 0)
            ->inRandomOrder()
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'unit' => $product->unit,
                    'stock' => $product->stock,
                    'image' => $product->image ? asset($product->image) : null,
                    // Discount fields
                    'has_discount' => $product->has_discount,
                    'discount_price' => $product->discount_price,
                    'discount_percentage' => $product->discount_percentage,
                    'discount_start_date' => $product->discount_start_date,
                    'discount_end_date' => $product->discount_end_date,
                ];
            });

        $bestSellers = Product::withCount(['transactionItems'])
            ->where('stock', '>', 0)
            ->orderBy('transaction_items_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'unit' => $product->unit,
                    'stock' => $product->stock,
                    'image' => $product->image ? asset($product->image) : null,
                    // Discount fields
                    'has_discount' => $product->has_discount,
                    'discount_price' => $product->discount_price,
                    'discount_percentage' => $product->discount_percentage,
                    'discount_start_date' => $product->discount_start_date,
                    'discount_end_date' => $product->discount_end_date,
                ];
            });

        // Get products with active discounts
        $discountProducts = Product::where('stock', '>', 0)
            ->where('has_discount', true)
            ->where('discount_start_date', '<=', now())
            ->where('discount_end_date', '>=', now())
            ->take(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'unit' => $product->unit,
                    'stock' => $product->stock,
                    'image' => $product->image ? asset($product->image) : null,
                    // Discount fields
                    'has_discount' => $product->has_discount,
                    'discount_price' => $product->discount_price,
                    'discount_percentage' => $product->discount_percentage,
                    'discount_start_date' => $product->discount_start_date,
                    'discount_end_date' => $product->discount_end_date,
                ];
            });

        return Inertia::render('Home', [
            'featuredProducts' => $featuredProducts,
            'bestSellers' => $bestSellers,
            'discountProducts' => $discountProducts,
        ]);
    }
}
