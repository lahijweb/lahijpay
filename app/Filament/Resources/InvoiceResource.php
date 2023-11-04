<?php

namespace App\Filament\Resources;

use App\Enums\InvoicePeopleTypeEnum;
use App\Enums\InvoiceStatusEnum;
use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Address;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoicePeople;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
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
                            ->live(onBlur: true)
                            ->placeholder('شماره فاکتور')
                            ->label('شماره فاکتور'),
                        Select::make('customer_id')
                            ->required()
                            ->relationship('customer', 'full_name')
                            ->searchable()
                            ->preload()
                            ->placeholder('مشتری')
                            ->live()
                            ->createOptionForm(function (Form $form) {
                                return $form
                                    ->schema([
                                        TextInput::make('first_name')
                                            ->required()
                                            ->label('نام'),
                                        TextInput::make('last_name')
                                            ->required()
                                            ->label('نام خانوادگی'),
                                        TextInput::make('mobile')
                                            ->label('موبایل'),
                                        TextInput::make('phone')
                                            ->label('تلفن'),
                                        TextInput::make('email')
                                            ->label('ایمیل'),
                                        TextInput::make('identity_no')
                                            ->label('شناسه / کد ملی'),
                                        TextInput::make('register_no')
                                            ->label('شماره ثبت'),
                                        TextInput::make('finance_no')
                                            ->label('شماره اقتصادی'),
                                        TextInput::make('bussiness_name')
                                            ->label('نام تجاری'),
                                    ])
                                    ->columns(3);
                            })
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $customer = Customer::find($state);
                                    $set('buyer.name', $customer->full_name);
                                    $set('buyer.identity_no', $customer->identity_no);
                                    $set('buyer.register_no', $customer->register_no);
                                    $set('buyer.finance_no', $customer->finance_no);
                                    $set('buyer.phone', $customer->mobile);
                                    $set('buyer.zip', null);
                                    $set('buyer.address', null);
                                    $set('address_id', null);
                                }
                            })
                            ->label('مشتری'),
                        Select::make('address_id')
                            ->options(function (Set $set, Get $get) {
                                $customer = Customer::find($get('customer_id'));
                                if ($customer)
                                    return $customer->addresses->mapWithKeys(function ($address) {
                                        return [
                                            $address->id => $address->full_address
                                        ];
                                    });
                                return [];
                            })
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                if ($state) {
                                    $address = Address::find($state);
                                    $set('buyer.zip', $address->zip);
                                    $set('buyer.address', $address->province . ' - ' . $address->city . ' - ' . $address->address);
                                }
                            })
                            ->createOptionUsing(function (Set $set, Get $get, array $data) {
                                $customer = Customer::find($get('customer_id'));
                                if ($customer) {
                                    $address = $customer->addresses()->create([
                                        'province' => $data['province'],
                                        'city' => $data['city'],
                                        'zip' => $data['zip'],
                                        'address' => $data['address'],
                                    ]);
                                    $set('address_id', $address->id);
                                    return $address->id;
                                }
                                Notification::make()
                                    ->title('خطا')
                                    ->body('مشتری یافت نشد! ابتدا مشتری را انتخاب کنید.')
                                    ->danger()
                                    ->send();
                                return null;
                            })
                            ->createOptionForm(function (Form $form) {
                                return $form
                                    ->schema([
                                        TextInput::make('province')
                                            ->required()
                                            ->label('استان'),
                                        TextInput::make('city')
                                            ->required()
                                            ->label('شهر'),
                                        TextInput::make('zip')
                                            ->label('کد پستی'),
                                        TextInput::make('address')
                                            ->required()
                                            ->columnSpan(3)
                                            ->label('آدرس'),
                                    ])
                                    ->columns(3);
                            })
                            ->live()
                            ->searchable()
                            ->label('آدرس مشتری'),
                        Select::make('seller_id')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('فروشنده')
                            ->relationship('seller', 'name')
                            ->getOptionLabelFromRecordUsing(fn(Model $record) => "{$record->id} - {$record->name}")
                            ->createOptionForm(function (Form $form) {
                                return $form
                                    ->schema([
                                        Hidden::make('type')
                                            ->default(InvoicePeopleTypeEnum::Seller),
                                        TextInput::make('name')
                                            ->required()
                                            ->label('نام فروشنده'),
                                        TextInput::make('identity_no')
                                            ->label('شناسه / کد ملی'),
                                        TextInput::make('register_no')
                                            ->label('شماره ثبت'),
                                        TextInput::make('finance_no')
                                            ->label('شماره اقتصادی'),
                                        TextInput::make('phone')
                                            ->label('تلفن'),
                                        TextInput::make('zip')
                                            ->label('کد پستی'),
                                        TextInput::make('address')
                                            ->columnSpan(3)
                                            ->label('آدرس'),
                                    ])
                                    ->columns(3);
                            })
                            ->label('فروشنده'),
                        Select::make('status')
                            ->required()
                            ->options(InvoiceStatusEnum::class)
                            ->default(InvoiceStatusEnum::Draft)
                            ->label('وضعیت'),
                        Placeholder::make('uuid')
                            ->content(fn(Invoice $record) => url('/invoice') . '/' . $record->uuid)
                            ->columnSpan(3)
                            ->hiddenOn(['create'])
                            ->label('لینک پرداخت فاکتور'),
                    ])
                    ->columns(3),
                Section::make('خریدار')
                    ->relationship('buyer')
                    ->schema([
                        Hidden::make('type')
                            ->default(InvoicePeopleTypeEnum::Buyer),
                        TextInput::make('name')
                            ->required()
                            ->label('نام خریدار'),
                        TextInput::make('identity_no')
                            ->label('شناسه / کد ملی'),
                        TextInput::make('register_no')
                            ->label('شماره ثبت'),
                        TextInput::make('finance_no')
                            ->label('شماره اقتصادی'),
                        TextInput::make('phone')
                            ->label('تلفن'),
                        TextInput::make('zip')
                            ->label('کد پستی'),
                        TextInput::make('address')
                            ->columnSpan(3)
                            ->label('آدرس'),
                    ])
                    ->collapsible()
                    ->columns(3),
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
