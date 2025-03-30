<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PriceListsRelationManager extends RelationManager
{
    protected static string $relationship = 'priceLists';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('PriceList')
            ->columns([
                TextColumn::make('country_code')->sortable()->searchable(),
                TextColumn::make('currency_code')->sortable()->searchable(),
                TextColumn::make('price')->sortable(),
                TextColumn::make('start_date')->sortable(),
                TextColumn::make('end_date')->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
