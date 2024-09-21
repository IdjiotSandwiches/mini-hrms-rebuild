<?php

namespace App\Interfaces;

interface UserInterface
{
    public const ID_COLUMN = 'id';
    public const UUID_COLUMN = 'uuid';
    public const FIRST_NAME_COLUMN = 'first_name';
    public const LAST_NAME_COLUMN = 'last_name';
    public const USERNAME_COLUMN = 'username';
    public const EMAIL_COLUMN = 'email';
    public const PASSWORD_COLUMN = 'password';
    public const AVATAR_COLUMN = 'avatar';
    public const LAST_PASSWORD_CHANGE_COLUMN = 'last_password_change';
}
