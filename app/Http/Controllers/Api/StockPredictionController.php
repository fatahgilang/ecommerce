<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StockPredictionService;
use Illuminate\Http\Request;

class StockPredictionController extends Controller
{
    protected $predictionService;

    public function __construct(StockPredictionService $predictionService)
    {
        $this->predictionService = $predictionService;
    }

    /**
     * Get all stock predictions
     */
    public function index()
    {
        $predictions = $this->predictionService->analyzeAndPredict();
        
        return response()->json([
            'success' => true,
            'data' => $predictions,
            'meta' => [
                'total' => count($predictions),
                'critical' => count(array_filter($predictions, fn($p) => $p['urgency'] === 'critical')),
                'high' => count(array_filter($predictions, fn($p) => $p['urgency'] === 'high')),
            ]
        ]);
    }

    /**
     * Get prediction for specific product
     */
    public function show($productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        $prediction = $this->predictionService->predictProductStock($product);
        
        if (!$prediction) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data penjualan untuk produk ini'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $prediction
        ]);
    }

    /**
     * Get dashboard insights
     */
    public function insights()
    {
        $insights = $this->predictionService->getDashboardInsights();
        
        return response()->json([
            'success' => true,
            'data' => $insights
        ]);
    }

    /**
     * Get critical stock items
     */
    public function critical()
    {
        $predictions = $this->predictionService->analyzeAndPredict();
        $critical = array_filter($predictions, fn($p) => $p['urgency'] === 'critical');
        
        return response()->json([
            'success' => true,
            'data' => array_values($critical),
            'count' => count($critical)
        ]);
    }
}
