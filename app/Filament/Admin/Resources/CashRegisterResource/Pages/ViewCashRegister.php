<?php

namespace App\Filament\Admin\Resources\CashRegisterResource\Pages;

use App\Filament\Admin\Resources\CashRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCashRegister extends ViewRecord
{
    protected static string $resource = CashRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),
        ];
    }
}