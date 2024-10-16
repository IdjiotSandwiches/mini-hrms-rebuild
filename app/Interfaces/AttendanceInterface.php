<?php

namespace App\Interfaces;

interface AttendanceInterface
{
    public const CHECK_IN_COLUMN = 'check_in';
    public const CHECK_OUT_COLUMN = 'check_out';
    public const LATE_COLUMN = 'late';
    public const EARLY_COLUMN = 'early';
    public const ABSENCE_COLUMN = 'absence';
    public const DATE_COLUMN = 'date';
}
