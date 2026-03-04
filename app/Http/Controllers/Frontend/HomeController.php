<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['shop', 'reviews'])
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->where('stock', '>', 0)
            ->inRandomOrder()
            ->take(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'unit' => $product->unit,
                    'stock' => $product->stock,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'shop' => $product->shop ? [
                        'shop_name' => $product->shop->shop_name,
                    ] : null,
                    'average_rating' => $product->reviews_avg_rating,
                    'reviews_count' => $product->reviews_count,
                ];
            });

        $bestSellers = Product::with(['shop', 'reviews'])
            ->withCount(['transactionItems', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->where('stock', '>', 0)
            ->orderBy('transaction_items_count', 'desc')
            ->take(8)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'product_price' => $product->product_price,
                    'unit' => $product->unit,
                    'stock' => $product->stock,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'shop' => $product->shop ? [
                        'shop_name' => $product->shop->shop_name,
                    ] : null,
                    'average_rating' => $product->reviews_avg_rating,
                    'reviews_count' => $product->reviews_count,
                ];
            });

        return Inertia::render('Home', [
            'featuredProducts' => $featuredProducts,
            'bestSellers' => $bestSellers,
        ]);
    }
}
