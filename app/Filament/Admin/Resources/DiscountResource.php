<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DiscountResource\Pages;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationLabel = 'Diskon';
    
    protected static ?string $navigationGroup = 'Manajemen POS';

    protected static ?string $modelLabel = 'Diskon';
    
    protected static ?string $pluralModelLabel = 'Diskon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Diskon')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label('Kode Diskon')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('Kosongkan jika tidak ada kode'),
                Forms\Components\Select::make('type')
                    ->label('Jenis Diskon')
                    ->options([
                        'percentage' => 'Persentase',
                        'fixed_amount' => 'Jumlah Tetap',
                    ])
                    ->required()
                    ->reactive(),
                Forms\Components\TextInput::make('value')
                    ->label('Nilai')
                    ->required()
                    ->numeric()
                    ->suffix(fn (callable $get) => $get('type') === 'percentage' ? '%' : 'Rp')
                    ->helperText(fn (callable $get) => $get('type') === 'percentage' ? 'Masukkan persentase (0-100)' : 'Masukkan jumlah tetap dalam Rupiah'),
                Forms\Components\TextInput::make('minimum_amount')
                    ->label('Minimum Pembelian')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->helperText('Minimum jumlah transaksi untuk mendapat diskon'),
                Forms\Components\TextInput::make('maximum_discount')
                    ->label('Maksimum Diskon')
                    ->numeric()
                    ->prefix('Rp')
                    ->helperText('Maksimum jumlah diskon (untuk jenis persentase)'),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->default(today()),
                Forms\Components\DatePicker::make('end_date')
                    ->label('Tanggal Berakhir')
                    ->required()
                    ->after('start_date'),
                Forms\Components\TextInput::make('usage_limit')
                    ->label('Batas Penggunaan')
                    ->numeric()
                    ->helperText('Kosongkan untuk penggunaan tidak terbatas'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Diskon')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->placeholder('Tanpa kode'),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Jenis')
                    ->colors([
                        'primary' => 'percentage',
                        'success' => 'fixed_amount',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'percentage' => 'Persentase',
                        'fixed_amount' => 'Jumlah Tetap',
                    }),
                Tables\Columns\TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(fn (string $state, $record): string => 
                        $record->type === 'percentage' ? $state . '%' : 'Rp ' . number_format($state, 0, ',', '.')
                    ),
                Tables\Columns\TextColumn::make('minimum_amount')
                    ->label('Min. Pembelian')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Berakhir')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('used_count')
                    ->label('Digunakan')
                    ->sortable(),
                Tables\Columns\TextColumn::make('usage_limit')
                    ->label('Batas')
                    ->placeholder('Tidak terbatas'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'percentage' => 'Persentase',
                        'fixed_amount' => 'Jumlah Tetap',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                Tables\Filters\Filter::make('active_period')
                    ->query(fn ($query) => $query->where('start_date', '<=', now())
                                                  ->where('end_date', '>=', now()))
                    ->label('Sedang Berlaku'),
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}