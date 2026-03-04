<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['shop', 'categories']);
        
        // Search dengan fulltext index
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            if (strlen($searchTerm) >= 3) {
                $query->whereRaw(
                    "MATCH(product_name, product_description) AGAINST(? IN BOOLEAN MODE)",
                    [$searchTerm . '*']
                );
            } else {
                $query->where('product_name', 'like', $searchTerm . '%');
            }
        }
        
        // Filter by shop
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        
        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('product_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('product_price', '<=', $request->max_price);
        }
        
        // Filter by stock availability
        if ($request->filled('in_stock')) {
            $query->where('stock', '>', 0);
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['product_name', 'product_price', 'stock', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }
        
        $products = $query->paginate($request->get('per_page', 12));
        
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