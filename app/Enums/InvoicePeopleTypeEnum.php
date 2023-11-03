<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum InvoicePeopleTypeEnum: string implements HasLabel
{
    case Seller = 'seller';
    case Buyer = 'buyer';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Seller => 'فروشنده',
            self::Buyer => 'خریدار',
        };
    }

}
