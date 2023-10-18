<?php

namespace App\Filament\Resources;

use App\Enums\ProductStatusEnum;
use App\Enums\ProductTypeEnum;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $pluralLabel = 'محصولات';
    protected static ?string $modelLabel = 'محصول';
    protected static ?string $navigationGroup = 'ابزارها';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->placeholder('عنوان محصول')
                                    ->label('عنوان')
                                    ->columnSpanFull(),
                                TextInput::make('slug')
                                    ->required()
                                    ->prefix(url('/') . '/product/')
                                    ->maxLength(255)
                                    ->placeholder('آدرس')
                                    ->unique(ignoreRecord: true)
                                    ->alphaDash()
                                    ->extraAttributes([
                                        'dir' => 'ltr'
                                    ])
                                    ->label('slug')
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->placeholder('توضیحات محصول')
                                    ->columnSpan(2)
                                    ->label('توضیحات')
                                    ->columnSpanFull(),
                            ]),
                        Section::make()
                            ->schema([
                                TextInput::make('sku')
                                    ->maxLength(255)
                                    ->placeholder('sku')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->label('sku'),
                                TextInput::make('qty')
                                    ->numeric('integer')
                                    ->inputMode('numeric')
                                    ->placeholder('موجودی')
                                    ->helperText('تعداد موجودی محصول، برای نامحدود این فیلد را خالی بگذارید.')
                                    ->label('موجودی'),
                                TextInput::make('price')
                                    ->numeric('integer')
                                    ->inputMode('numeric')
                                    ->placeholder('مبلغ')
                                    ->required()
                                    ->suffix('ریال')
                                    ->label('مبلغ'),
                            ])->columns(3)
                    ])->columnSpan(2),
                Forms\Components\Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('type')
                                    ->options(ProductTypeEnum::class)
                                    ->required()
                                    ->label('نوع محصول'),
                                Select::make('status')
                                    ->options(ProductStatusEnum::class)
                                    ->default('published')
                                    ->required()
                                    ->label('وضعیت'),
                                Toggle::make('is_active')
                                    ->inline(false)
                                    ->default(true)
                                    ->label('فعال'),
                                Toggle::make('get_address')
                                    ->inline(false)
                                    ->default(true)
                                    ->label('دریافت آدرس'),
                                Toggle::make('is_scheduled')
                                    ->inline(false)
                                    ->default(false)
                                    ->label('زمانبندی')
                                    ->live(),
                                DateTimePicker::make('start_date')
                                    ->visible(fn(Get $get): bool => $get('is_scheduled'))->jalali()
                                    ->required(fn(Get $get): bool => filled($get('is_scheduled')))
                                    ->placeholder('اعتبار فروش از تاریخ')
                                    ->label('اعتبار فروش از تاریخ'),
                                DateTimePicker::make('end_date')
                                    ->visible(fn(Get $get): bool => $get('is_scheduled'))->jalali()
                                    ->required(fn(Get $get): bool => filled($get('is_scheduled')))
                                    ->placeholder('اعتبار فروش تا تاریخ')
                                    ->label('اعتبار فروش تا تاریخ'),
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
                    ->toggleable()
                    ->sortable('desc')
                    ->label('شناسه'),
                TextColumn::make('sku')
                    ->searchable()
                    ->toggleable()
                    ->label('sku'),
                TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->label('slug'),
                TextColumn::make('title')
                    ->searchable()
                    ->toggleable()
                    ->label('عنوان'),
                TextColumn::make('type')
                    ->searchable()
                    ->toggleable()
                    ->label('نوع'),
                TextColumn::make('qty')
                    ->default('نامحدود')
                    ->toggleable()
                    ->label('موجودی'),
                TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->label('وضعیت'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->options(ProductStatusEnum::class)
                    ->label('وضعیت'),
                SelectFilter::make('type')
                    ->multiple()
                    ->searchable()
                    ->options(ProductTypeEnum::class)
                    ->label('نوع محصول'),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('از تاریخ')
                            ->jalali(),
                        DatePicker::make('created_until')
                            ->label('تا تاریخ')
                            ->jalali(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
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
            ->emptyStateHeading('محصولی یافت نشد!');
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['sku', 'slug', 'title', 'description'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->title;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'مصحول' => $record->title,
            'sku' => $record->sku,
            'slug' => $record->slug,
        ];
    }
}
