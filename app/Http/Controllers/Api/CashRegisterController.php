<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CashRegisterController extends Controller
{
    /**
     * Get current active cash register for user
     */
    public function current(Request $request): JsonResponse
    {
        $userId = $request->get('user_id', Auth::id());
        
        $cashRegister = CashRegister::where('user_id', $userId)
            ->where('status', 'open')
            ->with('user')
            ->first();

        if (!$cashRegister) {
            return response()->json([
                'message' => 'Tidak ada kasir aktif ditemukan',
                'cash_register' => null
            ]);
        }

        return response()->json([
            'cash_register' => [
                'id' => $cashRegister->id,
                'register_name' => $cashRegister->register_name,
                'opening_balance' => $cashRegister->opening_balance,
                'total_sales' => $cashRegister->total_sales,
                'total_cash' => $cashRegister->total_cash,
                'total_card' => $cashRegister->total_card,
                'total_ewallet' => $cashRegister->total_ewallet,
                'status' => $cashRegister->status,
                'opened_at' => $cashRegister->opened_at->format('d M Y H:i'),
                'cashier' => $cashRegister->user->name,
            ]
        ]);
    }

    /**
     * Open a new cash register
     */
    public function open(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'register_name' => 'required|string|max:255',
            'opening_balance' => 'required|numeric|min:0',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user already has an open register
        $existingRegister = CashRegister::where('user_id', $request->user_id)
            ->where('status', 'open')
            ->first();

        if ($existingRegister) {
            return response()->json([
                'message' => 'User sudah memiliki kasir yang terbuka'
            ], 422);
        }

        $cashRegister = CashRegister::create([
            'register_name' => $request->register_name,
            'user_id' => $request->user_id,
            'opening_balance' => $request->opening_balance,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        return response()->json([
            'message' => 'Kasir berhasil dibuka',
            'cash_register' => $cashRegister
        ], 201);
    }

    /**
     * Close cash register
     */
    public function close(Request $request, CashRegister $cashRegister): JsonResponse
    {
        if ($cashRegister->status === 'closed') {
            return response()->json([
                'message' => 'Kasir sudah ditutup'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'closing_balance' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $cashRegister->updateTotals();
        $cashRegister->close([
            'closing_balance' => $request->closing_balance,
            'notes' => $request->notes,
        ]);

        return response()->json([
            'message' => 'Kasir berhasil ditutup',
            'cash_register' => [
                'id' => $cashRegister->id,
                'opening_balance' => $cashRegister->opening_balance,
                'closing_balance' => $cashRegister->closing_balance,
                'total_sales' => $cashRegister->total_sales,
                'difference' => $cashRegister->closing_balance - $cashRegister->calculateClosingBalance(),
                'closed_at' => $cashRegister->closed_at->format('d M Y H:i'),
            ]
        ]);
    }

    /**
     * Get cash register history
     */
    public function history(Request $request): JsonResponse
    {
        $query = CashRegister::with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('opened_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('opened_at', '<=', $request->date_to);
        }

        $registers = $query->latest()->paginate($request->get('per_page', 10));

        $registers->getCollection()->transform(function ($register) {
            return [
                'id' => $register->id,
                'register_name' => $register->register_name,
                'cashier' => $register->user->name,
                'opening_balance' => $register->opening_balance,
                'closing_balance' => $register->closing_balance,
                'total_sales' => $register->total_sales,
                'status' => $register->status,
                'opened_at' => $register->opened_at->format('d M Y H:i'),
                'closed_at' => $register->closed_at?->format('d M Y H:i'),
                'duration' => $register->closed_at ? 
                    $register->opened_at->diffForHumans($register->closed_at, true) : 
                    $register->opened_at->diffForHumans(now(), true),
            ];
        });

        return response()->json($registers);
    }

    /**
     * Get cash register summary
     */
    public function summary(CashRegister $cashRegister): JsonResponse
    {
        $cashRegister->updateTotals();
        
        $transactionCount = $cashRegister->transactions()->where('status', 'completed')->count();
        $averageTransaction = $transactionCount > 0 ? 
            $cashRegister->total_sales / $transactionCount : 0;

        return response()->json([
            'register_info' => [
                'id' => $cashRegister->id,
                'register_name' => $cashRegister->register_name,
                'cashier' => $cashRegister->user->name,
                'status' => $cashRegister->status,
                'opened_at' => $cashRegister->opened_at->format('d M Y H:i'),
                'closed_at' => $cashRegister->closed_at?->format('d M Y H:i'),
            ],
            'financial_summary' => [
                'opening_balance' => $cashRegister->opening_balance,
                'closing_balance' => $cashRegister->closing_balance,
                'total_sales' => $cashRegister->total_sales,
                'total_cash' => $cashRegister->total_cash,
                'total_card' => $cashRegister->total_card,
                'total_ewallet' => $cashRegister->total_ewallet,
                'expected_balance' => $cashRegister->calculateClosingBalance(),
                'difference' => $cashRegister->closing_balance ? 
                    $cashRegister->closing_balance - $cashRegister->calculateClosingBalance() : 0,
            ],
            'transaction_summary' => [
                'total_transactions' => $transactionCount,
                'average_transaction' => $averageTransaction,
                'cancelled_transactions' => $cashRegister->transactions()->where('status', 'cancelled')->count(),
                'refunded_transactions' => $cashRegister->transactions()->where('status', 'refunded')->count(),
            ]
        ]);
    }
}