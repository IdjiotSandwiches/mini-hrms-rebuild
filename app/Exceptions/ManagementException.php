<?php

namespace App\Exceptions;

use Exception;

class ManagementException extends Exception
{
    public static function userNotFound()
    {
        return new self('Requested user not found.');
    }

    public static function cannotRemoveAdmin()
    {
        return new self('Administrator account cannot be deleted.');
    }

    public static function adminPasswordNotMatch()
    {
        return new self('Administrative password not match.');
    }
}
