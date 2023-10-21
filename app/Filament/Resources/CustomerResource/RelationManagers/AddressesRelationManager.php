<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\AddressResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'Addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->heading('آدرس‌ها')
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('شناسه'),
                TextColumn::make('province')
                    ->searchable()
                    ->toggleable()
                    ->label('استان'),
                TextColumn::make('city')
                    ->searchable()
                    ->toggleable()
                    ->label('شهر'),
                TextColumn::make('zip')
                    ->searchable()
                    ->toggleable()
                    ->default('-')
                    ->label('کد پستی'),
                TextColumn::make('address')
                    ->searchable()
                    ->toggleable()
                    ->default('-')
                    ->label('آدرس'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->recordUrl(fn(Model $record): string => AddressResource::getUrl('view', [
                'record' => $record,
            ]))
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('آدرسی یافت نشد!')
            ->emptyStateIcon('heroicon-o-banknotes');
    }
}
