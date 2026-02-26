<?php

namespace App\Enums;

enum Category: string
{
    case GENERAL = 'general';
    case PERSONAL = 'personal';
    case CASH = 'cash';
    case COMPANY = 'company';
    case MACHINE = 'machine';
    case SAVINGS = 'savings';
    case WALLET = 'wallet';

    public function label(): string
    {
        return match ($this) {
            self::GENERAL => 'عام',
            self::PERSONAL => 'شخصي',
            self::CASH => 'نقدي',
            self::COMPANY => 'شركة',
            self::MACHINE => 'الة سحب ذاتي',
            self::SAVINGS => 'ادخار',
            self::WALLET => 'محفظة',
        };
    }
}
