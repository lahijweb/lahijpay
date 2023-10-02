<?php

namespace App\Filament\Resources;

use App\Enums\PaymentStatusEnum;
use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Filament\Infolists\Components\TextEntry;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $pluralLabel = 'پرداخت‌ها';
    protected static ?string $modelLabel = 'پرداخت‌';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
                    ->label('شناسه'),
                TextColumn::make('uuid')
                    ->searchable()
                    ->limit(5)
                    ->toggleable()
                    ->label('UUID'),
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
                TextColumn::make('gateway.name')
                    ->searchable()
                    ->toggleable()
                    ->label('درگاه'),
                TextColumn::make('amount')
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
                    ->toggleable()
                    ->jalaliDateTime()
                    ->label('تاریخ ثبت'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->multiple()
                    ->searchable()
                    ->options(PaymentStatusEnum::class)
                    ->label('وضعیت'),
                SelectFilter::make('gateway_id')
                    ->multiple()
                    ->searchable()
                    ->relationship('gateway', 'name')
                    ->preload()
                    ->label('درگاه'),
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
            ])
            ->bulkActions([
                //
            ])
            ->emptyStateHeading('پرداختی یافت نشد!')
            ->emptyStateIcon('heroicon-o-banknotes');
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
            'index' => Pages\ListPayments::route('/'),
            'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('id')->label('شناسه'),
                TextEntry::make('uuid')->label('UUID'),
                TextEntry::make('first_name')->label('نام'),
                TextEntry::make('last_name')->label('نام خانوادگی')->default('-'),
                TextEntry::make('email')->label('ایمیل')->default('-'),
                TextEntry::make('mobile')->label('موبایل')->default('-'),
                TextEntry::make('description')->label('توضیحات')->default('-'),
                TextEntry::make('gateway.name')->label('درگاه'),
                TextEntry::make('amount')->money('IRR')->label('مبلغ'),
                TextEntry::make('status')->label('وضعیت'),
                TextEntry::make('transactionid')->label('شناسه تراکنش')->default('-'),
                TextEntry::make('referenceid')->label('شناسه مرجع')->default('-'),
                TextEntry::make('created_at')->label('تاریخ ثبت')->jalaliDateTime(),
                TextEntry::make('verified_at')->label('تاریخ تایید')->jalaliDateTime(),
                TextEntry::make('updated_at')->label('آخرین بروزرسانی')->jalaliDateTime(),
            ]);
    }
}
