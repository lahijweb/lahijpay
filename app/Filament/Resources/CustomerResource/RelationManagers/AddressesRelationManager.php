<?php

namespace App\Filament\Resources\CustomerResource\RelationManagers;

use App\Filament\Resources\AddressResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'Addresses';
    protected static ?string $title = 'آدرس‌ها';
    protected static ?string $label = 'آدرس';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
                TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('آدرس')
                    ->label('آدرس'),
                TextInput::make('zip')
                    ->maxLength(255)
                    ->placeholder('کد پستی')
                    ->label('کد پستی'),
                Toggle::make('is_active')
                    ->default(true)
                    ->label('فعال'),
                Toggle::make('is_default')
                    ->default(true)
                    ->label('پیش‌فرض'),
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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('-')
                    ->label('آدرس'),
                IconColumn::make('is_active')
                    ->boolean()
                    ->toggleable()
                    ->label('فعال'),
                IconColumn::make('is_default')
                    ->boolean()
                    ->toggleable()
                    ->label('پیش‌فرض'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modelLabel('آدرس جدید')
                    ->label('آدرس جدید'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->recordUrl(fn(Model $record): string => AddressResource::getUrl('view', [
                'record' => $record,
            ]))
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('آدرسی یافت نشد!')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
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
                IconEntry::make('is_active')
                    ->boolean()
                    ->label('فعال'),
                IconEntry::make('is_default')
                    ->boolean()
                    ->label('پیش‌فرض'),
                TextEntry::make('created_at')
                    ->jalaliDateTime()
                    ->label('تاریخ ایجاد'),
                TextEntry::make('updated_at')
                    ->jalaliDateTime()
                    ->label('تاریخ ویرایش'),
            ])
            ->columns(3);
    }

    public function isReadOnly(): bool
    {
        return false;
    }
}
