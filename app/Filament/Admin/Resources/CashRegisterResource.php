<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CashRegisterResource\Pages;
use App\Models\CashRegister;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class CashRegisterResource extends Resource
{
    protected static ?string $model = CashRegister::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    
    protected static ?string $navigationLabel = 'Kasir';
    
    protected static ?string $navigationGroup = 'Manajemen POS';

    protected static ?string $modelLabel = 'Kasir';
    
    protected static ?string $pluralModelLabel = 'Kasir';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('register_name')
                    ->label('Nama Kasir')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->label('Petugas Kasir'),
                Forms\Components\TextInput::make('opening_balance')
                    ->label('Saldo Awal')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Buka',
                        'closed' => 'Tutup',
                    ])
                    ->required()
                    ->default('open'),
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
                Tables\Columns\TextColumn::make('register_name')
                    ->label('Nama Kasir')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Petugas Kasir')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('opening_balance')
                    ->label('Saldo Awal')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_sales')
                    ->label('Total Penjualan')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'open',
                        'danger' => 'closed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Buka',
                        'closed' => 'Tutup',
                    }),
                Tables\Columns\TextColumn::make('opened_at')
                    ->label('Dibuka Pada')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('closed_at')
                    ->label('Ditutup Pada')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Masih buka'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Buka',
                        'closed' => 'Tutup',
                    ]),
                Tables\Filters\Filter::make('opened_today')
                    ->query(fn ($query) => $query->whereDate('opened_at', today()))
                    ->label('Dibuka Hari Ini'),
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
            ->defaultSort('opened_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Kasir')
                    ->schema([
                        Infolists\Components\TextEntry::make('register_name')
                            ->label('Nama Kasir'),
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('Petugas Kasir'),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'open' => 'success',
                                'closed' => 'danger',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'open' => 'Buka',
                                'closed' => 'Tutup',
                            }),
                        Infolists\Components\TextEntry::make('opened_at')
                            ->label('Dibuka Pada')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('closed_at')
                            ->label('Ditutup Pada')
                            ->dateTime()
                            ->placeholder('Masih buka'),
                    ])
                    ->columns(2),
                
                Infolists\Components\Section::make('Ringkasan Keuangan')
                    ->schema([
                        Infolists\Components\TextEntry::make('opening_balance')
                            ->label('Saldo Awal')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('closing_balance')
                            ->label('Saldo Akhir')
                            ->money('IDR')
                            ->placeholder('Belum ditutup'),
                        Infolists\Components\TextEntry::make('total_sales')
                            ->label('Total Penjualan')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_cash')
                            ->label('Total Tunai')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_card')
                            ->label('Total Kartu')
                            ->money('IDR'),
                        Infolists\Components\TextEntry::make('total_ewallet')
                            ->label('Total E-Wallet')
                            ->money('IDR'),
                    ])
                    ->columns(3),
                
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
            'index' => Pages\ListCashRegisters::route('/'),
            'create' => Pages\CreateCashRegister::route('/create'),
            'view' => Pages\ViewCashRegister::route('/{record}'),
            'edit' => Pages\EditCashRegister::route('/{record}/edit'),
        ];
    }
}