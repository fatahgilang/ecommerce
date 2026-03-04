<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction disabled - orders only created via frontend checkout
        ];
    }
    
    public function getHeading(): string
    {
        return 'Pesanan';
    }
    
    public function getSubheading(): ?string
    {
        return 'Pesanan dibuat otomatis saat customer melakukan checkout di frontend. Admin hanya bisa melihat dan mengupdate status pesanan.';
    }
}
