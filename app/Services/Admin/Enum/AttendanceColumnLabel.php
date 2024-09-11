<?php

namespace App\Services\Admin\Enum;

enum AttendanceColumnLabel: string
{
    case CHECK_IN_COLUMN = 'check_in';
    case CHECK_OUT_COLUMN = 'check_out';
    case LATE_COLUMN = 'late';
    case EARLY_COLUMN = 'early';
    case ABSENCE_COLUMN = 'absence';

    public static function columns(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): AttendanceColumnLabel
    {
        return match($this) {
            self::ABSENCE_COLUMN => AttendanceColumnLabel::ABSENCE_COLUMN,
        };
    }

    public static function test()
    {

    }
}
