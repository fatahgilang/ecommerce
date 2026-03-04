<?php

namespace App\Filament\Admin\Resources\CashRegisterResource\Pages;

use App\Filament\Admin\Resources\CashRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashRegisters extends ListRecords
{
    protected static string $resource = CashRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Buat Kasir Baru'),
        ];
    }
}