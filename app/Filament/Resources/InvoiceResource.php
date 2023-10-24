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
use Filament\Forms\Set;
use Filament\Forms\Components\Placeholder;
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
                            ->unique(ignoreRecord: true)
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
                Section::make()
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
                                    ->live()
                                    ->afterStateUpdated(function (Set $set, ?string $state) {
                                        if ($state) {
                                            $product = Product::find($state);
                                            $set('price', number_format($product->price, 0, '', ''));
                                        }
                                    })
                                    ->columnSpan(2)
                                    ->label('محصول'),
                                TextInput::make('qty')
                                    ->numeric()
                                    ->inputMode('numeric')
                                    ->required()
                                    ->live()
                                    ->default(1)
                                    ->step(1)
                                    ->minValue(1)
                                    ->label('تعداد'),
                                TextInput::make('price')
                                    ->default(0)
                                    ->numeric()
                                    ->inputMode('numeric')
                                    ->live()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('ریال')
                                    ->label('قیمت واحد'),
                                TextInput::make('discount')
                                    ->default(0)
                                    ->numeric()
                                    ->inputMode('numeric')
                                    ->live()
                                    ->required()
                                    ->minValue(0)
                                    ->columnSpan(2)
                                    ->suffix('ریال')
                                    ->label('تخفیف'),
                                TextInput::make('tax')
                                    ->numeric()
                                    ->default(0)
                                    ->live()
                                    ->required()
                                    ->minValue(0)
                                    ->suffix('%')
                                    ->label('مالیات'),
                                Placeholder::make('total')
                                    ->content(function ($get) {
                                        $total = $get('qty') * $get('price');
                                        $total -= $get('discount');
                                        $total += $total * ($get('tax') / 100);
                                        return number_format($total);
                                    })
                                    ->columnSpan(2)
                                    ->label('قیمت کل'),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $product = Product::find($data['product_id']);
                                $data['price'] = (int)$product->price;
                                $data['sku'] = $product->sku;
                                $data['title'] = $product->title;
                                $data['total'] = $data['qty'] * $data['price'];
                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                $product = Product::find($data['product_id']);
                                $data['price'] = (int)$product->price;
                                $data['sku'] = $product->sku;
                                $data['title'] = $product->title;
                                $data['total'] = $data['qty'] * $data['price'];
                                return $data;
                            })
                            ->minItems(1)
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->addActionLabel('افزودن محصول جدید')
                            ->columns(10)
                    ]),
                Section::make()
                    ->schema([
                        Placeholder::make('discount')
                            ->content(function ($get) {
                                $products = $get('products');
                                $total = 0;
                                foreach ($products as $item) {
                                    $discount = $item['discount'];
                                    if (is_numeric($discount))
                                        $total += $discount;
                                }
                                return number_format($total);
                            })->label('تخفیف'),
                        Placeholder::make('tax')
                            ->content(function ($get) {
                                $products = $get('products');
                                $total = 0;
                                foreach ($products as $item) {
                                    $total = $item['qty'] * $item['price'];
                                    $total -= $item['discount'];
                                    $total = $total * ($item['tax'] / 100);
                                }
                                return number_format($total);
                            })->label('مالیات'),
                        Placeholder::make('amount')
                            ->content(function ($get) {
                                $products = $get('products');
                                $total = 0;
                                foreach ($products as $item) {
                                    $total = $item['qty'] * $item['price'];
                                    $total -= $item['discount'];
                                    $total += $total * ($item['tax'] / 100);
                                }
                                return number_format($total);
                            })
                            ->label('مبلغ کل'),
                    ])->columns(3)
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
