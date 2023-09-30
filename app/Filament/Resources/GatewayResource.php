<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GatewayResource\Pages;
use App\Filament\Resources\GatewayResource\RelationManagers;
use App\Models\Gateway;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GatewayResource extends Resource
{
    protected static ?string $model = Gateway::class;
    protected static ?string $pluralLabel = 'درگاه';
    protected static ?string $modelLabel = 'درگاه';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('نام')
                    ->disabledOn('edit'),
                Forms\Components\KeyValue::make('config')
                    ->label('تنظیمات')
                    ->addable(false)
                    ->deletable(false)
                    ->editableKeys(false),
                Forms\Components\Toggle::make('is_default')
                    ->label('پیشفرض'),
                Forms\Components\Toggle::make('is_active')
                    ->label('فعال')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable('desc')
                    ->label('شناسه'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('نام'),
                Tables\Columns\ToggleColumn::make('is_default')
                    ->label('پیشفرض'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('فعال'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

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
            'index' => Pages\ListGateways::route('/'),
            'edit' => Pages\EditGateway::route('/{record}/edit'),
        ];
    }
}
