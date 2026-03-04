<?php

namespace App\Filament\Admin\Resources\CashRegisterResource\Pages;

use App\Filament\Admin\Resources\CashRegisterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashRegister extends EditRecord
{
    protected static string $resource = CashRegisterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('Lihat'),
            Actions\DeleteAction::make()
                ->label('Hapus'),
        ];
    }
}