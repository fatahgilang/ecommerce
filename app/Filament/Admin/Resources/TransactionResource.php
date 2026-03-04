<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    
    protected static ?string $navigationLabel = 'Transaksi';
    
    protected static ?string $navigationGroup = 'Manajemen POS';

    protected static ?string $modelLabel = 'Transaksi';
    
    protected static ?string $pluralModelLabel = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transaction_number')
                    ->label('Nomor Transaksi')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\Select::make('cash_register_id')
                    ->relationship('cashRegister', 'register_name')
                    ->label('Kasir')
                    ->required(),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Petugas Kasir'),
                Forms\Components\TextInput::make('subtotal')
                    ->label('Subtotal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\TextInput::make('tax_amount')
                    ->label('Pajak')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Forms\Components\TextInput::make('discount_amount')
                    ->label('Diskon')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Forms\Components\TextInput::make('total_amount')
                    ->label('Total')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\Select::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'card' => 'Kartu',
                        'ewallet' => 'E-Wallet',
                        'transfer' => 'Transfer',
                        'split' => 'Pembayaran Terpisah',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('paid_amount')
                    ->label('Jumlah Dibayar')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'refunded' => 'Dikembalikan',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction_number')
                    ->label('Nomor Transaksi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->placeholder('Pelanggan Umum'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Petugas Kasir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->colors([
                        'primary' => 'cash',
                        'success' => 'card',
                        'warning' => 'ewallet',
                        'info' => 'transfer',
                        'secondary' => 'split',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Tunai',
                        'card' => 'Kartu',
                        'ewallet' => 'E-Wallet',
                        'transfer' => 'Transfer',
                        'split' => 'Terpisah',
                    }),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'secondary' => 'refunded',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'refunded' => 'Dikembalikan',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        'refunded' => 'Dikembalikan',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->options([
                        'cash' => 'Tunai',
                        'card' => 'Kartu',
                        'ewallet' => 'E-Wallet',
                        'transfer' => 'Transfer',
                        'split' => 'Pembayaran Terpisah',
                    ]),
                Tables\Filters\Filter::make('today')
                    ->query(fn ($query) => $query->whereDate('created_at', today()))
                    ->label('Hari Ini'),
                Tables\Filters\Filter::make('this_week')
                    ->query(fn ($query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
                    ->label('Minggu Ini'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Transaksi')
                    ->schema([
                        Infolists\Components\TextEntry::make('transaction_number')
                            ->label('Nomor Transaksi'),
                        Infolists\Components\TextEntry::make('customer.name')
                            ->label('Pelanggan')
                            ->placeholder('Pelanggan Umum'),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Petugas Kasir'),
                        Infolists\Components\TextEntry::make('cashRegister.register_name')
                            ->label('Kasir'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'warning',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                'refunded' => 'secondary',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Menunggu',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                'refunded' => 'Dikembalikan',
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Tanggal Transaksi')
                            ->dateTime(),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Informasi Pembayaran')
                    ->schema([
                        Infolists\Components\TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('tax_amount')
                            ->label('Pajak')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('discount_amount')
                            ->label('Diskon')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total')
                            ->money('IDR')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'cash' => 'Tunai',
                                'card' => 'Kartu',
                                'ewallet' => 'E-Wallet',
                                'transfer' => 'Transfer',
                                'split' => 'Terpisah',
                            }),
                        Infolists\Components\TextEntry::make('paid_amount')
                            ->label('Jumlah Dibayar')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('change_amount')
                            ->label('Kembalian')
                            ->money('IDR'),
                    ])
                    ->columns(3),
                
                Infolists\Components\Section::make('Item Transaksi')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('items')
                            ->label('Daftar Item')
                            ->schema([
                                Infolists\Components\TextEntry::make('product.product_name')
                                    ->label('Produk'),
                                Infolists\Components\TextEntry::make('quantity')
                                    ->label('Jumlah'),
                                Infolists\Components\TextEntry::make('unit_price')
                                    ->label('Harga Satuan')
                                    ->money('IDR'),
                                Infolists\Components\TextEntry::make('discount_amount')
                                    ->label('Diskon')
                                    ->money('IDR'),
                                Infolists\Components\TextEntry::make('total_price')
                                    ->label('Total Harga')
                                    ->money('IDR'),
                            ])
                            ->columns(5),
                    ]),
                
                Infolists\Components\Section::make('Catatan')
                    ->schema([
                        Infolists\Components\TextEntry::make('notes')
                            ->label('Catatan')
                            ->placeholder('Tidak ada catatan'),
                    ])
                    ->collapsible(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'view' => Pages\ViewTransaction::route('/{record}'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}