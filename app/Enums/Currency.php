<?php

namespace App\Enums;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case LYD = 'LYD';

    public function label(): string
    {
        return match ($this) {
            self::USD => 'دولار أمريكي',
            self::EUR => 'يورو',
            self::LYD => 'دينار ليبي',
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::USD => '$',
            self::EUR => '€',
            self::LYD => 'د.ل',
        };
    }
}
