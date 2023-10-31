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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $modelLabel = 'فاکتور';
    protected static ?string $pluralLabel = 'فاکتورها';
    protected static ?string $navigationGroup = 'ابزارها';

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
                                    ->columnSpan(3)
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
                                    ->columnSpan(2)
                                    ->live()
                                    ->required()
                                    ->minValue(0)
                                    ->suffix('%')
                                    ->label('مالیات'),
                                Placeholder::make('total')
                                    ->content(function ($get) {
                                        $total = (float)$get('qty') * (float)$get('price') - (float)$get('discount');
                                        $total += $total * ((float)$get('tax') / 100);
                                        return number_format($total);
                                    })
                                    ->columnSpan(2)
                                    ->label('قیمت کل'),
                            ])
                            ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                                $product = Product::find($data['product_id']);
                                $data['sku'] = $product->sku;
                                $data['title'] = $product->title;
                                $total = $data['qty'] * $data['price'] - $data['discount'];
                                $data['total'] = $total + $total * ($data['tax'] / 100);
                                return $data;
                            })
                            ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                                $product = Product::find($data['product_id']);
                                $data['sku'] = $product->sku;
                                $data['title'] = $product->title;
                                $total = $data['qty'] * $data['price'] - $data['discount'];
                                $data['total'] = $total + $total * ($data['tax'] / 100);
                                return $data;
                            })
                            ->itemLabel(fn(array $state): ?string => Product::find($state['product_id'])['title'] ?? null)
                            ->minItems(1)
                            ->reorderable()
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->addActionLabel('افزودن محصول جدید')
                            ->columns(12)
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
                                $totalTax = 0;
                                foreach ($products as $item) {
                                    $total = (float)$item['qty'] * (float)$item['price'] - (float)$item['discount'];
                                    $totalTax += $total * ((float)$item['tax'] / 100);
                                }
                                return number_format($totalTax);
                            })->label('مالیات'),
                        Placeholder::make('amount')
                            ->content(function ($get) {
                                $products = $get('products');
                                $totalPrice = 0;
                                foreach ($products as $item) {
                                    $total = (float)$item['qty'] * (float)$item['price'] - (float)$item['discount'];
                                    $totalPrice += $total + $total * ((float)$item['tax'] / 100);
                                }
                                return number_format($totalPrice);
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
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable()
                    ->label('شناسه'),
                TextColumn::make('invoice_no')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('شماره فاکتور'),
                TextColumn::make('uuid')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('شناسه یکتا'),
                TextColumn::make('customer.full_name')
                    ->searchable()
                    ->toggleable()
                    ->label('مشتری'),
                TextColumn::make('total')
                    ->searchable()
                    ->toggleable()
                    ->money('IRR')
                    ->label('مبلغ'),
                TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->label('وضعیت'),
                TextColumn::make('created_at')
                    ->jalaliDateTime()
                    ->toggleable(isToggledHiddenByDefault: true)
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])
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

    public static function canEdit(Model $record): bool
    {
        if ($record->status === InvoiceStatusEnum::Paid)
            return false;
        return true;
    }
}
