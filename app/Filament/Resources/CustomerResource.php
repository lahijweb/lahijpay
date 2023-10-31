<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;
    protected static ?string $modelLabel = 'مشتری';
    protected static ?string $pluralModelLabel = 'مشتریان';
    protected static ?string $pluralLabel = 'مشتریان';
    protected static ?string $navigationGroup = 'ابزارها';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('نام')
                                    ->label('نام'),
                                TextInput::make('last_name')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('نام خانوادگی')
                                    ->label('نام خانوادگی'),
                                TextInput::make('email')
                                    ->maxLength(255)
                                    ->email()
                                    ->placeholder('ایمیل')
                                    ->label('ایمیل'),
                                TextInput::make('mobile')
                                    ->maxLength(255)
                                    ->placeholder('موبایل')
                                    ->label('موبایل'),
                                TextInput::make('phone')
                                    ->maxLength(255)
                                    ->placeholder('تلفن')
                                    ->label('تلفن'),
                                TextInput::make('identity_no')
                                    ->maxLength(255)
                                    ->placeholder('کد/شناسه ملی')
                                    ->label('کد/شناسه ملی'),
                                TextInput::make('register_no')
                                    ->maxLength(255)
                                    ->placeholder('شماره ثبت')
                                    ->label('شماره ثبت'),
                                TextInput::make('finance_no')
                                    ->maxLength(255)
                                    ->placeholder('شماره اقتصادی')
                                    ->label('شماره اقتصادی'),
                                TextInput::make('business_name')
                                    ->maxLength(255)
                                    ->placeholder('نام کسب‌وکار')
                                    ->label('نام کسب‌وکار'),
                                Textarea::make('additional_Info')
                                    ->columnSpanFull()
                                    ->placeholder('اطلاعات تکمیلی')
                                    ->label('اطلاعات تکمیلی'),
                            ])->columns(2)
                    ])->columnSpan(['lg' => 2]),
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Toggle::make('is_active')
                                    ->inline(false)
                                    ->default(true)
                                    ->label('فعال'),
                                Toggle::make('is_business')
                                    ->inline(false)
                                    ->default(false)
                                    ->label('شخص حقوقی'),
                            ])
                    ])->columnSpan(['lg' => 1])
            ])->columns(3);
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
                TextColumn::make('first_name')
                    ->searchable()
                    ->toggleable()
                    ->label('نام'),
                TextColumn::make('last_name')
                    ->searchable()
                    ->toggleable()
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
                TextColumn::make('phone')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->default('-')
                    ->label('تلفن'),
                TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->jalaliDateTime()
                    ->label('تاریخ ایجاد'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->emptyStateHeading('مشتریی یافت نشد!');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'view' => Pages\ViewCustomer::route('/{record}'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
            'mobile',
            'phone',
            'identity_no',
            'register_no',
            'finance_no',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->first_name . ' ' . $record->last_name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'مشتری' => $record->first_name . ' ' . $record->last_name,
            'ایمیل' => $record->email,
            'موبایل' => $record->mobile,
        ];
    }
}
