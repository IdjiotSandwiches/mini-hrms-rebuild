<?php

namespace App\Exceptions;

use Exception;

class TakeAttendanceException extends Exception
{
    public static function alreadyTakeAttendance()
    {
        return new self('You have already taken attendance today.');
    }

    public static function minimumWork()
    {
        return new self('You need at least 1 hour to check out.');
    }

    public static function noSchedules()
    {
        return new self('Configure your schedule first.');
    }

    public static function noWorkSchedule()
    {
        return new self('You do not have work schedule today.');
    }
}
