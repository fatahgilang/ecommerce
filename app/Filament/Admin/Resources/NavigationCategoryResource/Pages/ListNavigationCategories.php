<?php

namespace App\Filament\Admin\Resources\NavigationCategoryResource\Pages;

use App\Filament\Admin\Resources\NavigationCategoryResource;
use App\Models\NavigationCategory;
use App\Models\ProductCategory;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListNavigationCategories extends ListRecords
{
    protected static string $resource = NavigationCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('sync_categories')
                ->label('Sinkronisasi Kategori')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->action(function () {
                    $this->syncCategoriesFromProducts();
                })
                ->requiresConfirmation()
                ->modalHeading('Sinkronisasi Kategori Produk')
                ->modalDescription('Ini akan membuat kategori navigasi baru berdasarkan kategori produk yang ada. Kategori yang sudah ada tidak akan diubah.')
                ->modalSubmitActionLabel('Sinkronisasi'),
            
            Actions\CreateAction::make(),
        ];
    }

    protected function syncCategoriesFromProducts(): void
    {
        // Get unique product categories
        $productCategories = ProductCategory::select('category_name')
            ->distinct()
            ->whereNotIn('category_name', ['Aksesoris', 'Audio', 'Gaming', 'Gadget', 'Komputer', 'Komunikasi', 'Pakaian', 'Footwear', 'Bag', 'Wallet', 'Timepiece', 'Eyewear', 'Minuman', 'Makanan', 'Beverage', 'Cemilan', 'Dessert', 'Instant', 'Dairy', 'Bakery', 'Peralatan', 'Sport Equipment', 'Fitness', 'Yoga', 'Gym', 'Cardio', 'Outdoor', 'Cycling', 'Suplemen', 'Wellness', 'Skincare', 'Beauty', 'Personal Care', 'Hair Care', 'Fragrance', 'Hygiene', 'Peralatan Makan', 'Kitchen', 'Cutlery', 'Peralatan Masak', 'Cookware', 'Kitchen Appliance', 'Produk', 'General'])
            ->pluck('category_name');

        $created = 0;
        $sortOrder = NavigationCategory::max('sort_order') + 1;

        foreach ($productCategories as $categoryName) {
            // Skip if already exists
            if (NavigationCategory::where('name', $categoryName)->exists()) {
                continue;
            }

            // Create new navigation category
            NavigationCategory::create([
                'name' => $categoryName,
                'slug' => \Illuminate\Support\Str::slug($categoryName),
                'description' => "Kategori untuk produk {$categoryName}",
                'is_active' => false, // Inactive by default, admin can activate manually
                'sort_order' => $sortOrder++,
                'icon' => $this->getDefaultIcon($categoryName),
                'color' => $this->getDefaultColor($categoryName),
            ]);

            $created++;
        }

        if ($created > 0) {
            Notification::make()
                ->title('Sinkronisasi Berhasil')
                ->body("Berhasil membuat {$created} kategori navigasi baru. Silakan aktifkan kategori yang ingin ditampilkan di frontend.")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Tidak Ada Kategori Baru')
                ->body('Semua kategori produk sudah tersedia sebagai kategori navigasi.')
                ->info()
                ->send();
        }
    }

    protected function getDefaultIcon(string $categoryName): string
    {
        $iconMap = [
            'Elektronik' => 'heroicon-o-computer-desktop',
            'Fashion' => 'heroicon-o-sparkles',
            'Makanan & Minuman' => 'heroicon-o-cake',
            'Kesehatan & Kecantikan' => 'heroicon-o-heart',
            'Rumah Tangga' => 'heroicon-o-home',
            'Olahraga' => 'heroicon-o-trophy',
        ];

        return $iconMap[$categoryName] ?? 'heroicon-o-star';
    }

    protected function getDefaultColor(string $categoryName): string
    {
        $colorMap = [
            'Elektronik' => '#3B82F6',
            'Fashion' => '#EC4899',
            'Makanan & Minuman' => '#F59E0B',
            'Kesehatan & Kecantikan' => '#EF4444',
            'Rumah Tangga' => '#10B981',
            'Olahraga' => '#8B5CF6',
        ];

        return $colorMap[$categoryName] ?? '#6B7280';
    }
}
