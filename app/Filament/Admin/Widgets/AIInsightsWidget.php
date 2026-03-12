<?php

namespace App\Filament\Admin\Widgets;

use App\Services\StockPredictionService;
use Filament\Widgets\Widget;

class AIInsightsWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.ai-insights-widget';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = 1;

    public function getInsights(): array
    {
        $service = new StockPredictionService();
        return $service->getDashboardInsights();
    }
}
