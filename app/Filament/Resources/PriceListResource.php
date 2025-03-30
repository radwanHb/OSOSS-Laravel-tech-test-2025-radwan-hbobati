<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PriceListResource\Pages;
use App\Models\PriceList;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class PriceListResource extends Resource
{
    protected static ?string $model = PriceList::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('product_id')
                ->relationship('product', 'name')
                ->required(),

            Select::make('country_code')
                ->options(fn () => \App\Models\Country::pluck('code', 'code')->toArray())
                ->searchable(),

            Select::make('currency_code')
                ->options(fn () => \App\Models\Currency::pluck('code', 'code')->toArray())
                ->searchable(),

            TextInput::make('price')->numeric()->required(),
            DatePicker::make('start_date'),
            DatePicker::make('end_date'),
            TextInput::make('priority')->numeric()->default(1),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('product.name')->sortable()->searchable(),
            TextColumn::make('country_code')->sortable()->searchable(),
            TextColumn::make('currency_code')->sortable()->searchable(),
            TextColumn::make('price')->sortable(),
            TextColumn::make('start_date')->sortable(),
            TextColumn::make('end_date')->sortable(),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPriceLists::route('/'),
            'create' => Pages\CreatePriceList::route('/create'),
            'edit' => Pages\EditPriceList::route('/{record}/edit'),
        ];
    }
}
