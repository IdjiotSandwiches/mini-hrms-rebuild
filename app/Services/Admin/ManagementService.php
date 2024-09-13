<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\BaseService;

class ManagementService extends BaseService
{
    public function getUserList($users)
    {
        $users = $users->paginate(10, ['*'], 'user')
            ->through(function ($user) {
                return $this->convertUserData($user);
            });

        return $users;
    }

    public function convertUserData($user)
    {
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $username = $user->username;
        $email = $user->email;

        return (object) compact('firstName', 'lastName', 'username', 'email');
    }
}
