<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use App\Models\ProductCategory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function afterCreate(): void
    {
        $categories = $this->data['categories'] ?? [];
        
        foreach ($categories as $categoryName) {
            ProductCategory::create([
                'product_id' => $this->record->id,
                'category_name' => $categoryName,
            ]);
        }
    }
}
