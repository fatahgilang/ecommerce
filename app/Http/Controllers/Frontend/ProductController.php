<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('stock', '>', 0);

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            
            // Gunakan fulltext search untuk pencarian yang lebih akurat
            // Jika search term lebih dari 3 karakter, gunakan fulltext
            if (strlen($searchTerm) >= 3) {
                $query->whereRaw(
                    "MATCH(product_name, product_description) AGAINST(? IN BOOLEAN MODE)",
                    [$searchTerm . '*']
                );
            } else {
                // Untuk search term pendek, gunakan LIKE dengan index
                $query->where('product_name', 'like', $searchTerm . '%');
            }
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('category_name', $request->category);
            });
        }

        // Categories filter (multiple)
        if ($request->filled('categories')) {
            $categories = is_array($request->categories) ? $request->categories : [$request->categories];
            $query->whereHas('categories', function ($q) use ($categories) {
                $q->whereIn('category_name', $categories);
            });
        }

        // Price range
        if ($request->filled('min_price')) {
            $query->where('product_price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('product_price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('product_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('product_price', 'desc');
                break;
            case 'popular':
                $query->withCount('transactionItems')
                    ->orderBy('transaction_items_count', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->through(function ($product) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_price' => $product->product_price,
                'unit' => $product->unit,
                'stock' => $product->stock,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                // Discount fields
                'has_discount' => $product->has_discount,
                'discount_price' => $product->discount_price,
                'discount_percentage' => $product->discount_percentage,
                'discount_start_date' => $product->discount_start_date,
                'discount_end_date' => $product->discount_end_date,
            ];
        });

        return Inertia::render('Products/Index', [
            'products' => $products,
            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'categories' => $request->categories,
                'minPrice' => $request->min_price,
                'maxPrice' => $request->max_price,
                'sort' => $request->get('sort', 'newest'),
            ],
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['categories'])
            ->findOrFail($id);

        return Inertia::render('Products/Show', [
            'product' => [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_description' => $product->product_description,
                'product_price' => $product->product_price,
                'unit' => $product->unit,
                'stock' => $product->stock,
                'weight' => $product->weight ?? 0,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'categories' => $product->categories->pluck('category_name'),
                // Discount fields
                'has_discount' => $product->has_discount,
                'discount_price' => $product->discount_price,
                'discount_percentage' => $product->discount_percentage,
                'discount_start_date' => $product->discount_start_date,
                'discount_end_date' => $product->discount_end_date,
            ],
        ]);
    }
}
