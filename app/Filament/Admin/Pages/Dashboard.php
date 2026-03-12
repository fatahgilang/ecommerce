<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Admin\Widgets\AIInsightsWidget;
use App\Filament\Admin\Widgets\StockPredictionWidget;
use App\Filament\Admin\Widgets\StatsOverview;
use App\Filament\Admin\Widgets\SalesChart;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.admin.pages.dashboard';
    
    protected static ?string $title = 'Dashboard';
    
    protected static ?string $navigationLabel = 'Dashboard';
    
    public function getColumns(): int | string | array
    {
        return [
            'sm' => 1,
            'md' => 2,
            'xl' => 4,
        ];
    }
    
    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            AIInsightsWidget::class,
            StockPredictionWidget::class,
            SalesChart::class,
        ];
    }
}
