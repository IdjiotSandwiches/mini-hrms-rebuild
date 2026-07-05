<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case AUTH = 'auth';

    public static function columns(): array
    {
        return array_column(self::cases(), 'value');
    }
}
