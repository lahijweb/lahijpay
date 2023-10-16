<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PayableTypeEnum: string implements HasLabel
{
    case Payment = 'App\\Models\\Payment';
    case Link = 'App\\Models\\Link';
    case Order = 'App\\Models\\Order';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Payment => 'پرداخت',
            self::Link => 'لینک',
            self::Order => 'سفارش',
        };
    }

}
