<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductTypeEnum: string implements HasLabel
{
    case Physical = 'physical';
    case Digital = 'digital';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Physical => 'فیزیکی',
            self::Digital => 'دیجیتال',
        };
    }

}
