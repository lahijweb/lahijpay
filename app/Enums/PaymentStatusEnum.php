<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatusEnum: string implements HasLabel, HasColor
{
    case Pending = 'pending';
    case Failed = 'failed';
    case Paid = 'paid';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'در حال بررسی',
            self::Failed => 'ناموفق',
            self::Paid => 'موفق',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Failed => 'danger',
            self::Paid => 'success',
        };
    }
}
