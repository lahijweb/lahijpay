<?php

namespace App\Filament\Resources;

use App\Enums\InvoiceStatusEnum;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $modelLabel = 'فاکتور';
    protected static ?string $pluralLabel = 'فاکتورها';
    protected static ?string $navigationGroup = 'ابزارها';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('invoice_no')
                            ->required()
                            ->unique()
                            ->placeholder('شماره فاکتور')
                            ->label('شماره فاکتور'),
                        Select::make('customer_id')
                            ->required()
                            ->relationship('customer', 'full_name')
                            ->searchable()
                            ->preload()
                            ->placeholder('مشتری')
                            ->label('مشتری'),
                        Select::make('status')
                            ->required()
                            ->options(InvoiceStatusEnum::class)
                            ->default(InvoiceStatusEnum::Draft)
                            ->label('وضعیت'),
                    ])->columns(3),
                Section::make('')
                    ->schema([
                        Repeater::make('products')
                            ->relationship()
                            ->label('محصولات')
                            ->schema([
                                Select::make('product_id')
                                    ->relationship('product', 'title')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('محصول'),
                                TextInput::make('product_sku')
                                    ->label('قیمت واحد'),
                                TextInput::make('product_qty')
                                    ->integer()
                                    ->required()
                                    ->default(1)
                                    ->minValue(1)
                                    ->label('تعداد'),
                                TextInput::make('product_price')
                                    ->label('قیمت واحد'),
                            ])
                            ->minItems(1)
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->addActionLabel('افزودن محصول جدید')
                            ->columns(3)
                    ])
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
                TextColumn::make('invoice_no')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('شماره فاکتور'),
                TextColumn::make('uuid')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('شناسه یکتا'),
                TextColumn::make('customer.full_name')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('مشتری'),
                TextColumn::make('total')
                    ->searchable()
                    ->toggleable()
                    ->money('IRR')
                    ->sortable()
                    ->label('مبلغ'),
                TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('وضعیت'),
                TextColumn::make('created_at')
                    ->jalaliDateTime()
                    ->toggleable()
                    ->sortable()
                    ->label('تاریخ ایجاد'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->options(InvoiceStatusEnum::class)
                    ->label('وضعیت'),
                SelectFilter::make('customer_id')
                    ->multiple()
                    ->searchable()
                    ->relationship('customer', 'full_name')
                    ->preload()
                    ->label('مشتری'),
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
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('فاکتوری یافت نشد!');
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
