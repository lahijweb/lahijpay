<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PaymentStatusEnum: string implements HasLabel, HasColor
{
    case Pending = 'PENDING';
    case Accepted = 'ACCEPTED';
    case Rejected = 'REJECTED';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'در حال بررسی',
            self::Accepted => 'تایید شده',
            self::Rejected => 'رد شده',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Pending => 'gray',
            self::Accepted => 'success',
            self::Rejected => 'warning',
        };
    }
}
