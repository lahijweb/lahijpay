<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductStatusEnum: string implements HasLabel, HasColor
{
    case Draft = 'draft';
    case Published = 'published';
    case Unpublished = 'unpublished';
    case Archived = 'archived';


    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'پیش نویس',
            self::Published => 'منتشر شده',
            self::Unpublished => 'منتشر نشده',
            self::Archived => 'بایگانی شده',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Published => 'green',
            self::Unpublished => 'red',
            self::Archived => 'gray',
        };
    }
}
