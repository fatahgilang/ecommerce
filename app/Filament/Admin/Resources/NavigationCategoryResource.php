<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NavigationCategoryResource\Pages;
use App\Models\NavigationCategory;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NavigationCategoryResource extends Resource
{
    protected static ?string $model = NavigationCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-3';

    protected static ?string $navigationLabel = 'Kategori Navigasi';
    
    protected static ?string $navigationGroup = 'Pengaturan Frontend';

    protected static ?string $modelLabel = 'Kategori Navigasi';
    
    protected static ?string $pluralModelLabel = 'Kategori Navigasi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', \Illuminate\Support\Str::slug($state)))
                            ->helperText('Nama kategori yang akan ditampilkan di navbar frontend'),
                        
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(NavigationCategory::class, 'slug', ignoreRecord: true)
                            ->helperText('URL-friendly version dari nama kategori (otomatis dibuat)'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->helperText('Deskripsi singkat tentang kategori ini'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Pengaturan Tampilan')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->helperText('Kategori aktif akan ditampilkan di navbar frontend'),
                        
                        Forms\Components\TextInput::make('sort_order')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0)
                            ->helperText('Angka lebih kecil akan ditampilkan lebih dulu'),
                        
                        Forms\Components\Select::make('icon')
                            ->label('Ikon')
                            ->options([
                                'heroicon-o-computer-desktop' => 'Elektronik',
                                'heroicon-o-sparkles' => 'Fashion',
                                'heroicon-o-cake' => 'Makanan & Minuman',
                                'heroicon-o-heart' => 'Kesehatan & Kecantikan',
                                'heroicon-o-home' => 'Rumah Tangga',
                                'heroicon-o-trophy' => 'Olahraga',
                                'heroicon-o-academic-cap' => 'Pendidikan',
                                'heroicon-o-wrench-screwdriver' => 'Peralatan',
                                'heroicon-o-gift' => 'Hadiah',
                                'heroicon-o-star' => 'Umum',
                            ])
                            ->searchable()
                            ->helperText('Ikon yang akan ditampilkan di samping nama kategori'),
                        
                        Forms\Components\ColorPicker::make('color')
                            ->label('Warna')
                            ->default('#6B7280')
                            ->helperText('Warna untuk badge dan highlight kategori'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Preview Produk')
                    ->schema([
                        Forms\Components\Placeholder::make('products_count')
                            ->label('Jumlah Produk')
                            ->content(function (?NavigationCategory $record): string {
                                if (!$record) {
                                    return 'Belum ada data';
                                }
                                
                                $count = \App\Models\Product::whereHas('categories', function ($query) use ($record) {
                                    $query->where('category_name', $record->name);
                                })->count();
                                
                                return $count . ' produk ditemukan dengan kategori ini';
                            }),
                    ])
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Urutan')
                    ->sortable()
                    ->width(80),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug disalin!')
                    ->color('gray'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Jumlah Produk')
                    ->getStateUsing(function (NavigationCategory $record): int {
                        return \App\Models\Product::whereHas('categories', function ($query) use ($record) {
                            $query->where('category_name', $record->name);
                        })->count();
                    })
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state < 5 => 'warning',
                        default => 'success',
                    }),
                
                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua Kategori')
                    ->trueLabel('Aktif')
                    ->falseLabel('Tidak Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNavigationCategories::route('/'),
            'create' => Pages\CreateNavigationCategory::route('/create'),
            'edit' => Pages\EditNavigationCategory::route('/{record}/edit'),
        ];
    }
}