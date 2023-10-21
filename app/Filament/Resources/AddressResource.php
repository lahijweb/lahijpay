<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers;
use App\Models\Address;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;
    protected static ?string $modelLabel = 'آدرس';
    protected static ?string $pluralLabel = 'آدرس‌ها';
    protected static ?string $navigationGroup = 'ابزارها';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('customer_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->relationship('customer', 'full_name')
                            ->label('مشتری'),
                        TextInput::make('province')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('استان')
                            ->label('استان'),
                        TextInput::make('city')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('شهر')
                            ->label('شهر'),
                        TextInput::make('zip')
                            ->maxLength(255)
                            ->placeholder('کد پستی')
                            ->label('کد پستی'),
                        TextInput::make('address')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->placeholder('آدرس')
                            ->label('آدرس'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('شناسه'),
                TextColumn::make('customer.full_name')
                    ->searchable()
                    ->toggleable()
                    ->label('مشتری'),
                TextColumn::make('province')
                    ->searchable()
                    ->toggleable()
                    ->label('استان'),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable()
                    ->label('شهر'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('customer')
                    ->multiple()
                    ->searchable()
                    ->relationship('customer', 'full_name')
                    ->preload()
                    ->label('مشتری'),

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('آدرسی یافت نشد!');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('آدرس')
                    ->schema([
                        TextEntry::make('customer.full_name')
                            ->url(fn($record): string => CustomerResource::getUrl('view', [$record->customer_id]))
                            ->label('مشتری'),
                        TextEntry::make('province')
                            ->label('استان'),
                        TextEntry::make('city')
                            ->label('شهر'),
                        TextEntry::make('zip')
                            ->default('-')
                            ->label('کد پستی'),
                        TextEntry::make('address')
                            ->columnSpanFull()
                            ->label('آدرس'),
                    ])->columns(2)
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
            'index' => Pages\ListAddresses::route('/'),
            'create' => Pages\CreateAddress::route('/create'),
            'view' => Pages\ViewAddress::route('/{record}'),
            'edit' => Pages\EditAddress::route('/{record}/edit'),
        ];
    }
}
