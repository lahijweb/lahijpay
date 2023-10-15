<?php

namespace App\Filament\Resources\OrderStatusResource\Pages;

use App\Filament\Resources\OrderStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderStatus extends EditRecord
{
    protected static string $resource = OrderStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
