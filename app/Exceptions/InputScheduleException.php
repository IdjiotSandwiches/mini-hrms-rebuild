<?php

namespace App\Exceptions;

use Exception;

class InputScheduleException extends Exception
{
    public static function doesNotMeetHoursRequirement()
    {
        return new self('You must work at least 20 hours a week.');
    }
}
