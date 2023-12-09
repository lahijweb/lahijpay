<?php

namespace App\Filament\Resources\LinkResource\RelationManagers;

use App\Enums\PayableTypeEnum;
use App\Enums\PaymentStatusEnum;
use App\Filament\Resources\LinkResource;
use App\Filament\Resources\PaymentResource;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments';
    protected static ?string $title = 'پرداخت‌ها';
    protected static ?string $label = 'پرداخت';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->heading('پرداخت‌ها')
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->toggleable()
                    ->sortable()
                    ->label('شناسه'),
                TextColumn::make('uuid')
                    ->searchable()
                    ->limit(5)
                    ->toggleable()
                    ->toggledHiddenByDefault()
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
                    ->toggledHiddenByDefault()
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
            ->recordUrl(
                fn(Model $record): string => PaymentResource::getUrl('view', ['record' => $record]),
            )
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('پرداختی یافت نشد!')
            ->emptyStateDescription('')
            ->emptyStateIcon('heroicon-o-banknotes');
    }
}
