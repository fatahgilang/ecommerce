<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OrderResource\Pages;
use App\Filament\Admin\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Pesanan';
    
    protected static ?string $navigationGroup = 'Manajemen Toko';

    protected static ?string $modelLabel = 'Pesanan';
    
    protected static ?string $pluralModelLabel = 'Pesanan';
    
    // Disable create action - orders only created via frontend checkout
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pesanan')
                    ->description('Data pesanan yang dibuat melalui checkout frontend')
                    ->schema([
                        Forms\Components\Select::make('product_id')
                            ->relationship('product', 'product_name')
                            ->label('Produk')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('product_quantity')
                            ->label('Jumlah Produk')
                            ->disabled()
                            ->dehydrated(false)
                            ->numeric(),
                        Forms\Components\TextInput::make('total_price')
                            ->label('Total Harga')
                            ->disabled()
                            ->dehydrated(false)
                            ->prefix('Rp')
                            ->numeric(),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Status & Pembayaran')
                    ->description('Update status pesanan dan metode pembayaran')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status Pesanan')
                            ->required()
                            ->options([
                                'pending' => 'Menunggu Pembayaran',
                                'paid' => 'Sudah Dibayar',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ])
                            ->native(false),
                        Forms\Components\Select::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->required()
                            ->options([
                                'bca' => 'Bank BCA',
                                'mandiri' => 'Bank Mandiri',
                                'bri' => 'Bank BRI',
                                'gopay' => 'GoPay',
                                'ovo' => 'OVO',
                                'dana' => 'DANA',
                            ])
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Pesanan')
                    ->sortable()
                    ->searchable()
                    ->prefix('#'),
                Tables\Columns\TextColumn::make('product.product_name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_quantity')
                    ->label('Jumlah')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Harga')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'bca', 'mandiri', 'bri' => 'info',
                        'gopay', 'ovo', 'dana' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'info',
                        'processing' => 'primary',
                        'shipped' => 'success',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pesanan')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'bca' => 'BCA',
                        'mandiri' => 'Mandiri',
                        'bri' => 'BRI',
                        'gopay' => 'GoPay',
                        'ovo' => 'OVO',
                        'dana' => 'DANA',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'), // Disabled - orders only created via frontend checkout
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
