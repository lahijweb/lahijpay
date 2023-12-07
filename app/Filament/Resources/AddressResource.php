<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AddressResource\Pages;
use App\Filament\Resources\AddressResource\RelationManagers;
use App\Models\Address;
use App\Models\Customer;
use Filament\Actions\ActionGroup;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\IconEntry;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        MorphToSelect::make('related')
                            ->types([
                                MorphToSelect\Type::make(Customer::class)
                                    ->label('مشتری')
                                    ->titleAttribute('full_name'),
                            ])
                            ->required()
                            ->searchable()
                            ->columnSpanFull()
                            ->preload()
                            ->label('برای'),
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
                TextColumn::make('related_type')
                    ->toggleable()
                    ->label('نوع کاربر'),
                TextColumn::make('full_name')
                    ->toggleable()
                    ->label('نام کاربر'),
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
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ])
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
                Group::make()
                    ->schema([
                        InfolistSection::make('آدرس')
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
                            ])
                            ->columns(2)
                            ->columnSpan(['lg' => 2]),
                        InfolistSection::make('کاربر')
                            ->schema([
                                TextEntry::make('related_type')
                                    ->label('نوع کاربر'),
                                TextEntry::make('related.id')
                                    ->label('شناسه کاربر'),
                                TextEntry::make('full_name')
                                    ->label('نام کاربر'),
                            ])
                            ->columns(2)
                            ->columnSpan(['lg' => 2]),
                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => 2]),
                InfolistSection::make()
                    ->schema([
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
                    ->columnSpan(['lg' => 1]),

            ])->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('related');
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
