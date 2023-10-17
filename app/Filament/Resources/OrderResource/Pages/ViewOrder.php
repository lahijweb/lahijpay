<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderStatus;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('updateStatus')
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
        ];
    }
}
