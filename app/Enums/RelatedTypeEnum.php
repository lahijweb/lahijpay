<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RelatedTypeEnum: string implements HasLabel
{
    case Customer = 'App\Models\Customer';
    case User = 'App\Models\User';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Customer => 'مشتری',
            self::User => 'مدیر',
        };
    }
}
