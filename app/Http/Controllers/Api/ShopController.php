<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShopController extends Controller
{
    /**
     * Display a listing of shops.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Shop::query();

        // Search
        if ($request->has('search')) {
            $query->where('shop_name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->get('per_page', 12);
        $shops = $query->latest()->paginate($perPage);

        $shops->getCollection()->transform(function ($shop) {
            return [
                'id' => $shop->id,
                'shop_name' => $shop->shop_name,
                'description' => $shop->description,
                'address' => $shop->address,
                'phone' => $shop->phone,
                'email' => $shop->email,
                'logo' => $shop->logo ? asset('storage/' . $shop->logo) : null,
                'total_products' => $shop->total_products,
                'active_products_count' => $shop->active_products_count,
                'average_rating' => $shop->average_rating,
                'total_sales' => $shop->total_sales,
            ];
        });

        return response()->json($shops);
    }

    /**
     * Display the specified shop.
     */
    public function show(Shop $shop): JsonResponse
    {
        $shop->load(['activeProducts']);

        return response()->json([
            'id' => $shop->id,
            'shop_name' => $shop->shop_name,
            'description' => $shop->description,
            'address' => $shop->address,
            'phone' => $shop->phone,
            'email' => $shop->email,
            'logo' => $shop->logo ? asset('storage/' . $shop->logo) : null,
            'total_products' => $shop->total_products,
            'active_products_count' => $shop->active_products_count,
            'average_rating' => $shop->average_rating,
            'total_sales' => $shop->total_sales,
            'created_at' => $shop->created_at->format('d M Y'),
        ]);
    }

    /**
     * Get shop's products.
     */
    public function products(Request $request, Shop $shop): JsonResponse
    {
        $query = $shop->activeProducts()->inStock();

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('product_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('product_price', 'desc');
                break;
            case 'popular':
                $query->popular();
                break;
            default:
                $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 12);
        $products = $query->paginate($perPage);

        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'product_description' => $product->product_description,
                'product_price' => $product->product_price,
                'formatted_price' => $product->formatted_price,
                'stock' => $product->stock,
                'in_stock' => $product->in_stock,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'average_rating' => $product->average_rating,
                'reviews_count' => $product->reviews_count,
            ];
        });

        return response()->json($products);
    }
}