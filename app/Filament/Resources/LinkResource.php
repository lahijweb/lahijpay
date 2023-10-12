<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Filament\Resources\LinkResource\RelationManagers;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Get;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;
    protected static ?string $pluralLabel = 'لینک‌ها';
    protected static ?string $modelLabel = 'لینک';
    protected static ?string $navigationGroup = 'ابزارها';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->placeholder('عنوان لینک')
                            ->label('عنوان'),
                        TextInput::make('description')
                            ->required()
                            ->placeholder('توضیحات لینک')
                            ->label('توضیحات'),
                        TextInput::make('slug')
                            ->required()
                            ->suffix(url('/') . '/link/')
                            ->maxLength(255)
                            ->placeholder('آدرس')
                            ->unique(ignoreRecord: true)
                            ->alphaDash()
                            ->label('slug'),
                        TextInput::make('max_uses')
                            ->numeric()
                            ->inputMode('numeric')
                            ->helperText('حداکثر تعداد پرداخت با استفاده از این لینک، برای استفاده نامحدود فیلد خالی باشد')
                            ->placeholder('حداکثر تعداد استفاده')
                            ->label('حداکثر تعداد استفاده'),
                        Toggle::make('is_active')
                            ->inline(false)
                            ->default(true)
                            ->label('فعال'),
                        Toggle::make('is_scheduled')
                            ->inline(false)
                            ->default(false)
                            ->label('زمانبندی')
                            ->live(),
                        DateTimePicker::make('start_date')
                            ->visible(fn(Get $get): bool => $get('is_scheduled'))->jalali()
                            ->required(fn(Get $get): bool => filled($get('is_scheduled')))
                            ->placeholder('اعتبار لینک از تاریخ')
                            ->label('اعتبار لینک از تاریخ'),
                        DateTimePicker::make('end_date')
                            ->visible(fn(Get $get): bool => $get('is_scheduled'))->jalali()
                            ->required(fn(Get $get): bool => filled($get('is_scheduled')))
                            ->placeholder('اعتبار لینک تا تاریخ')
                            ->label('اعتبار لینک تا تاریخ'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable('desc')
                    ->label('شناسه'),
                TextColumn::make('title')
                    ->searchable()
                    ->label('عنوان'),
                TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->label('slug'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('لینکی یافت نشد!');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'view' => Pages\ViewLink::route('/{record}'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }


    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['slug', 'title', 'description'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'slug' => $record->slug,
        ];
    }

}
