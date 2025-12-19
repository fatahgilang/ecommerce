<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): JsonResponse
    {
        $customers = Customer::paginate($request->get('per_page', 10));
        
        return response()->json($customers);
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'email' => 'required|email|unique:customers,email',
            'date_of_birth' => 'required|date',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer = Customer::create($validator->validated());

        return response()->json([
            'message' => 'Customer created successfully',
            'customer' => $customer
        ], 201);
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string',
            'email' => 'sometimes|email|unique:customers,email,' . $customer->id,
            'date_of_birth' => 'sometimes|date',
            'phone' => 'sometimes|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $customer->update($validator->validated());

        return response()->json([
            'message' => 'Customer updated successfully',
            'customer' => $customer
        ]);
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully'
        ]);
    }

    /**
     * Display the customer's orders.
     */
    public function orders(Customer $customer): JsonResponse
    {
        $orders = $customer->orders()->with(['product', 'shipment'])->paginate(10);
        
        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'product' => [
                    'id' => $order->product->id,
                    'name' => $order->product->product_name,
                    'price' => $order->product->product_price,
                ],
                'quantity' => $order->product_quantity,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'created_at' => $order->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json($orders);
    }

    /**
     * Display the customer's reviews.
     */
    public function reviews(Customer $customer): JsonResponse
    {
        $reviews = $customer->reviews()->with('product')->paginate(10);
        
        $reviews->getCollection()->transform(function ($review) {
            return [
                'id' => $review->id,
                'product' => [
                    'id' => $review->product->id,
                    'name' => $review->product->product_name,
                ],
                'rating' => $review->rating,
                'comment' => $review->comment,
                'created_at' => $review->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json($reviews);
    }
}