<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\OrderStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $pluralLabel = 'سفارش‌ها';
    protected static ?string $modelLabel = 'سفارش‌';
    protected static ?string $navigationGroup = 'گزارش‌ها';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                TextColumn::make('product.title')
                    ->searchable()
                    ->toggleable()
                    ->limit(10)
                    ->label('محصول'),
                TextColumn::make('first_name')
                    ->searchable()
                    ->toggleable()
                    ->default('-')
                    ->label('نام'),
                TextColumn::make('last_name')
                    ->searchable()
                    ->toggleable()
                    ->default('-')
                    ->label('نام خانوادگی'),
                TextColumn::make('email')
                    ->searchable()
                    ->toggleable()
                    ->default('-')
                    ->label('ایمیل'),
                TextColumn::make('mobile')
                    ->searchable()
                    ->toggleable()
                    ->default('-')
                    ->label('موبایل'),
                TextColumn::make('total_price')
                    ->searchable()
                    ->toggleable()
                    ->money('IRR')
                    ->label('مبلغ کل'),
                TextColumn::make('status.title')
                    ->searchable()
                    ->toggleable()
                    ->label('وضعیت'),
                TextColumn::make('created_at')
                    ->toggleable()
                    ->jalaliDateTime()
                    ->label('تاریخ ثبت'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->relationship('status', 'title')
                    ->preload()
                    ->label('وضعیت'),
                SelectFilter::make('product')
                    ->multiple()
                    ->searchable()
                    ->relationship('product', 'title')
                    ->preload()
                    ->label('محصول'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->label('مشاهده'),
                    Tables\Actions\EditAction::make()->label('ویرایش'),
                    Tables\Actions\Action::make('updateStatus')
                        ->form([
                            Select::make('status_id')
                                ->label('وضعیت')
                                ->options(OrderStatus::query()->pluck('title', 'id'))
                                ->default(fn(Order $record): int => $record->status_id)
                                ->required(),
                        ])
                        ->action(function (array $data, Order $record): void {
                            $record->status()->associate($data['status_id']);
                            $record->save();
                            Notification::make()
                                ->title('وضعیت سفارش با موفقیت بروزرسانی شد.')
                                ->success()
                                ->send();
                        })
                        ->label('بروزرسانی وضعیت')
                        ->icon('heroicon-m-pencil-square'),
                ])
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('سفارشی یافت نشد.');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('اطلاعات سفارش')
                    ->schema([
                        TextEntry::make('id')
                            ->label('شناسه سفارش'),
                        TextEntry::make('payment_id')
                            ->default('-')
                            ->url(fn(Model $record): string => PaymentResource::getUrl('view', ['record' => $record->payment ?? '?']))
                            ->label('شناسه پرداخت'),
                        TextEntry::make('payment.status')
                            ->default('-')
                            ->label('وضعیت پرداخت'),
                        TextEntry::make('product.title')
                            ->url(fn(Model $record): string => ProductResource::getUrl('view', ['record' => $record->product]))
                            ->label('محصول'),
                        TextEntry::make('qty')
                            ->label('تعداد'),
                        TextEntry::make('total_price')
                            ->money('IRR')
                            ->label('مبلغ کل'),
                        TextEntry::make('status.title')
                            ->label('وضعیت سفارش'),
                        TextEntry::make('created_at')
                            ->jalaliDateTime()
                            ->label('تاریخ ثبت'),
                        TextEntry::make('payment.verified_at')
                            ->jalaliDateTime()
                            ->label('تاریخ پرداخت'),
                    ])
                    ->columns(3)
                    ->collapsible(),
                Section::make('خریدار')
                    ->schema([
                        TextEntry::make('first_name')
                            ->label('نام'),
                        TextEntry::make('last_name')
                            ->default('-')
                            ->label('نام خانوادگی'),
                        TextEntry::make('email')
                            ->default('-')
                            ->label('ایمیل'),
                        TextEntry::make('mobile')
                            ->default('-')
                            ->label('موبایل'),
                        TextEntry::make('province')
                            ->default('-')
                            ->label('استان'),
                        TextEntry::make('city')
                            ->default('-')
                            ->label('شهر'),
                        TextEntry::make('zip')
                            ->default('-')
                            ->label('کد پستی'),
                        TextEntry::make('address')
                            ->default('-')
                            ->columnSpan(2)
                            ->label('آدرس'),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => Pages\ViewOrder::route('/{record}'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'mobile',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->first_name . ' ' . $record->last_name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'پرداخت کننده' => $record->first_name . ' ' . $record->last_name,
            'ایمیل' => $record->email,
            'موبایل' => $record->mobile,
        ];
    }
}
