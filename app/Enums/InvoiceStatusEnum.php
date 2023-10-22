<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvoiceStatusEnum: string implements HasLabel, HasColor
{
    case Draft = 'draft';
    case Unpaid = 'unpaid';
    case Paid = 'paid';
    case Canceled = 'canceled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'پیش نویس',
            self::Unpaid => 'پرداخت نشده',
            self::Paid => 'پرداخت شده',
            self::Canceled => 'لغو شده',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'primary',
            self::Unpaid => 'danger',
            self::Paid => 'success',
            self::Canceled => 'warning',
        };
    }
}
