<?php

namespace App\Filament\Admin\Widgets;

use App\Services\StockPredictionService;
use Filament\Widgets\Widget;

class StockPredictionWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.stock-prediction-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 2;

    public function getPredictions(): array
    {
        $service = new StockPredictionService();
        $predictions = $service->analyzeAndPredict();
        
        // Return only top 10 most urgent
        return array_slice($predictions, 0, 10);
    }
}
