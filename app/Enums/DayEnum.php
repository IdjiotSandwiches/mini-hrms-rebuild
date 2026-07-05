<?php

namespace App\Enums;

enum DayEnum: string
{
    case MONDAY = 'Monday';
    case TUESDAY = 'Tuesday';
    case WEDNESDAY = 'Wednesday';
    case THURSDAY = 'Thursday';
    case FRIDAY = 'Friday';
    case SATURDAY = 'Saturday';
    case SUNDAY =  'Sunday';

    public static function columns(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromIso(int $isoDay): self
    {
        return match($isoDay) {
            1 => self::MONDAY,
            2 => self::TUESDAY,
            3 => self::WEDNESDAY,
            4 => self::THURSDAY,
            5 => self::FRIDAY,
            6 => self::SATURDAY,
            7 => self::SUNDAY,
            default => throw new \InvalidArgumentException("Invalid ISO day: {$isoDay}"),
        };
    }
}
