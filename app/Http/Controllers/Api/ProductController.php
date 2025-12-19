<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['shop', 'categories'])
            ->paginate(12);
        
        return response()->json($products);
    }

    public function show(Product $product)
    {
        $product->load(['shop', 'categories', 'reviews.customer']);
        
        return response()->json($product);
    }

    public function reviews(Product $product)
    {
        $reviews = $product->reviews()
            ->with('customer')
            ->latest()
            ->paginate(10);
        
        return response()->json($reviews);
    }

    public function addReview(Request $request, Product $product)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $review = $product->reviews()->create($validated);
        
        return response()->json($review, 201);
    }
}