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
                                'cash' => 'Tunai',
                                'bca' => 'Bank BCA',
                                'mandiri' => 'Bank Mandiri',
                                'bri' => 'Bank BRI',
                                'gopay' => 'GoPay',
                                'ovo' => 'OVO',
                                'dana' => 'DANA',
                            ])
                            ->native(false),
                        Forms\Components\TextInput::make('discount_code')
                            ->label('Kode Diskon')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Tidak ada diskon'),
                        Forms\Components\TextInput::make('discount_amount')
                            ->label('Jumlah Diskon')
                            ->disabled()
                            ->dehydrated(false)
                            ->prefix('Rp')
                            ->numeric()
                            ->placeholder('0'),
                    ])
                    ->columns(2),
                    
                Forms\Components\Section::make('Informasi Transaksi')
                    ->description('Transaksi yang dibuat otomatis saat pesanan dibayar')
                    ->schema([
                        Forms\Components\TextInput::make('transaction.transaction_number')
                            ->label('Nomor Transaksi')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Belum ada transaksi'),
                        Forms\Components\TextInput::make('processedBy.name')
                            ->label('Diproses Oleh')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Belum diproses'),
                        Forms\Components\DateTimePicker::make('processed_at')
                            ->label('Waktu Diproses')
                            ->disabled()
                            ->dehydrated(false)
                            ->placeholder('Belum diproses'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record && $record->transaction_id),
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
                Tables\Columns\TextColumn::make('discount_code')
                    ->label('Kode Diskon')
                    ->placeholder('-')
                    ->badge()
                    ->color('warning')
                    ->searchable(),
                Tables\Columns\TextColumn::make('discount_amount')
                    ->label('Diskon')
                    ->money('IDR')
                    ->placeholder('Rp 0')
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'gray',
                        'bca', 'mandiri', 'bri' => 'info',
                        'gopay', 'ovo', 'dana' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'TUNAI',
                        default => strtoupper($state),
                    })
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
                Tables\Columns\TextColumn::make('transaction.transaction_number')
                    ->label('No. Transaksi')
                    ->searchable()
                    ->placeholder('-')
                    ->copyable()
                    ->copyMessage('Nomor transaksi disalin!')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('processedBy.name')
                    ->label('Diproses Oleh')
                    ->placeholder('-')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('processed_at')
                    ->label('Waktu Diproses')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Dari Tanggal')
                            ->placeholder('Pilih tanggal mulai'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Sampai Tanggal')
                            ->placeholder('Pilih tanggal akhir'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['created_from'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Dari: ' . \Carbon\Carbon::parse($data['created_from'])->format('d M Y'))
                                ->removeField('created_from');
                        }
                        if ($data['created_until'] ?? null) {
                            $indicators[] = Tables\Filters\Indicator::make('Sampai: ' . \Carbon\Carbon::parse($data['created_until'])->format('d M Y'))
                                ->removeField('created_until');
                        }
                        return $indicators;
                    }),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'paid' => 'Sudah Dibayar',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'bca' => 'BCA',
                        'mandiri' => 'Mandiri',
                        'bri' => 'BRI',
                        'gopay' => 'GoPay',
                        'ovo' => 'OVO',
                        'dana' => 'DANA',
                    ])
                    ->multiple(),
                Tables\Filters\TernaryFilter::make('today')
                    ->label('Hari Ini')
                    ->placeholder('Semua Pesanan')
                    ->trueLabel('Pesanan Hari Ini')
                    ->falseLabel('Bukan Hari Ini')
                    ->queries(
                        true: fn (Builder $query) => $query->whereDate('created_at', today()),
                        false: fn (Builder $query) => $query->whereDate('created_at', '!=', today()),
                    ),
            ])
            ->filtersLayout(Tables\Enums\FiltersLayout::AboveContent)
            ->persistFiltersInSession()
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
