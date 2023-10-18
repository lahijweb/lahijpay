<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GatewayResource\Pages;
use App\Filament\Resources\GatewayResource\RelationManagers;
use App\Models\Gateway;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class GatewayResource extends Resource
{
    protected static ?string $model = Gateway::class;
    protected static ?string $pluralLabel = 'درگاه‌ها';
    protected static ?string $modelLabel = 'درگاه';
    protected static ?string $navigationGroup = 'تنظیمات';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make()
                        ->schema([
                            TextInput::make('name')
                                ->label('نام')
                                ->disabledOn('edit')
                                ->columnSpanFull(),
                            KeyValue::make('config')
                                ->label('تنظیمات')
                                ->addable(false)
                                ->deletable(false)
                                ->editableKeys(false)
                                ->columnSpanFull(),
                        ])
                ])->columnSpan(2),
                Group::make()->schema([
                    Section::make()
                        ->schema([
                            Toggle::make('is_default')
                                ->label('پیشفرض'),
                            Toggle::make('is_active')
                                ->label('فعال'),
                        ])->columns(1)
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable('desc')
                    ->label('شناسه'),
//                TextColumn::make('driver')
//                    ->searchable()
//                    ->label('درایور'),
                TextColumn::make('name')
                    ->searchable()
                    ->label('نام'),
                ToggleColumn::make('is_default')
                    ->label('پیشفرض'),
                ToggleColumn::make('is_active')
                    ->label('فعال'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
