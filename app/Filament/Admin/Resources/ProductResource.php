<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Filament\Admin\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Produk';
    
    protected static ?string $navigationGroup = 'Manajemen Toko';

    protected static ?string $modelLabel = 'Produk';
    
    protected static ?string $pluralModelLabel = 'Produk';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_name')
                    ->label('Nama Produk')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('product_description')
                    ->label('Deskripsi Produk')
                    ->rows(3),
                Forms\Components\TextInput::make('product_price')
                    ->label('Harga Produk')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('unit')
                    ->label('Satuan')
                    ->required()
                    ->default('pcs'),
                Forms\Components\TextInput::make('stock')
                    ->label('Stok')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\FileUpload::make('image')
                    ->label('Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->imageEditor()
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                    ->helperText('Upload gambar produk (max 2MB, format: JPG, PNG, WEBP)'),
                
                Forms\Components\Section::make('Kategori Produk')
                    ->schema([
                        Forms\Components\TagsInput::make('categories')
                            ->label('Kategori')
                            ->placeholder('Ketik kategori dan tekan Enter')
                            ->suggestions([
                                'Elektronik',
                                'Fashion',
                                'Makanan & Minuman',
                                'Kesehatan & Kecantikan',
                                'Rumah Tangga',
                                'Komputer',
                                'Gaming',
                                'Audio',
                                'Gadget',
                                'Pakaian',
                                'Footwear',
                                'Aksesoris',
                                'Minuman',
                                'Makanan',
                                'Cemilan',
                                'Personal Care',
                                'Skincare',
                                'Kitchen',
                                'Peralatan Masak',
                            ])
                            ->helperText('Tambahkan kategori untuk produk ini. Kategori utama akan digunakan untuk navigasi frontend.')
                            ->afterStateHydrated(function (Forms\Components\TagsInput $component, $state, $record) {
                                if ($record) {
                                    $categories = $record->categories->pluck('category_name')->toArray();
                                    $component->state($categories);
                                }
                            }),
                    ])
                    ->collapsible(),
                
                Forms\Components\Section::make('Pengaturan Diskon')
                    ->schema([
                        Forms\Components\Toggle::make('has_discount')
                            ->label('Aktifkan Diskon')
                            ->reactive(),
                        Forms\Components\TextInput::make('discount_price')
                            ->label('Harga Diskon')
                            ->numeric()
                            ->prefix('Rp')
                            ->visible(fn (Forms\Get $get) => $get('has_discount'))
                            ->helperText('Kosongkan jika menggunakan persentase diskon')
                            ->minValue(0)
                            ->rules([
                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if (!$value) return; // Skip if empty (nullable)
                                    
                                    $originalPrice = $get('product_price');
                                    if ($originalPrice && $value >= $originalPrice) {
                                        $fail('Harga diskon harus lebih kecil dari harga asli.');
                                    }
                                },
                            ]),
                        Forms\Components\TextInput::make('discount_percentage')
                            ->label('Persentase Diskon (%)')
                            ->numeric()
                            ->suffix('%')
                            ->minValue(0)
                            ->maxValue(100)
                            ->visible(fn (Forms\Get $get) => $get('has_discount'))
                            ->helperText('Kosongkan jika menggunakan harga diskon tetap'),
                        Forms\Components\DatePicker::make('discount_start_date')
                            ->label('Tanggal Mulai Diskon')
                            ->visible(fn (Forms\Get $get) => $get('has_discount'))
                            ->default(now())
                            ->required(fn (Forms\Get $get) => $get('has_discount')),
                        Forms\Components\DatePicker::make('discount_end_date')
                            ->label('Tanggal Berakhir Diskon')
                            ->visible(fn (Forms\Get $get) => $get('has_discount'))
                            ->default(now()->addDays(30))
                            ->required(fn (Forms\Get $get) => $get('has_discount'))
                            ->after('discount_start_date'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->size(60)
                    ->defaultImageUrl(null),
                Tables\Columns\TextColumn::make('product_name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('main_category')
                    ->label('Kategori')
                    ->getStateUsing(function (Product $record): string {
                        $mainCategories = ['Elektronik', 'Fashion', 'Makanan & Minuman', 'Kesehatan & Kecantikan', 'Rumah Tangga'];
                        $productCategories = $record->categories->pluck('category_name');
                        
                        foreach ($mainCategories as $mainCategory) {
                            if ($productCategories->contains($mainCategory)) {
                                return $mainCategory;
                            }
                        }
                        
                        return $productCategories->first() ?? 'Tidak ada kategori';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Elektronik' => 'info',
                        'Fashion' => 'success',
                        'Makanan & Minuman' => 'warning',
                        'Kesehatan & Kecantikan' => 'danger',
                        'Rumah Tangga' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('product_price')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('current_price')
                    ->label('Harga Saat Ini')
                    ->money('IDR')
                    ->getStateUsing(fn (Product $record): float => $record->getCurrentPrice())
                    ->badge()
                    ->color(fn (Product $record): string => $record->hasActiveDiscount() ? 'success' : 'gray'),
                Tables\Columns\IconColumn::make('has_discount')
                    ->label('Diskon')
                    ->boolean()
                    ->trueIcon('heroicon-o-tag')
                    ->falseIcon('heroicon-o-minus')
                    ->trueColor('success')
                    ->falseColor('gray'),
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label('Diskon (%)')
                    ->getStateUsing(fn (Product $record): string => $record->hasActiveDiscount() ? number_format($record->getDiscountPercentage(), 1) . '%' : '-')
                    ->badge()
                    ->color(fn (Product $record): string => $record->hasActiveDiscount() ? 'warning' : 'gray'),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 10 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'Elektronik' => 'Elektronik',
                        'Fashion' => 'Fashion',
                        'Makanan & Minuman' => 'Makanan & Minuman',
                        'Kesehatan & Kecantikan' => 'Kesehatan & Kecantikan',
                        'Rumah Tangga' => 'Rumah Tangga',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $category): Builder => $query->whereHas('categories', function ($q) use ($category) {
                                $q->where('category_name', $category);
                            })
                        );
                    }),
                Tables\Filters\TernaryFilter::make('has_discount')
                    ->label('Produk Diskon')
                    ->placeholder('Semua Produk')
                    ->trueLabel('Ada Diskon')
                    ->falseLabel('Tanpa Diskon'),
                Tables\Filters\Filter::make('low_stock')
                    ->label('Stok Rendah')
                    ->query(fn (Builder $query): Builder => $query->where('stock', '<', 10))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
 public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
