<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use App\Models\ProductCategory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        // Delete existing categories
        ProductCategory::where('product_id', $this->record->id)->delete();
        
        // Add new categories
        $categories = $this->data['categories'] ?? [];
        
        foreach ($categories as $categoryName) {
            ProductCategory::create([
                'product_id' => $this->record->id,
                'category_name' => $categoryName,
            ]);
        }
    }
}
