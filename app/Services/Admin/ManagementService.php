<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Builder;

class ManagementService extends BaseService
{
    public function getUserList(User|Builder $users)
    {
        $users = $users->paginate(10, ['*'], 'user')
            ->through(function ($user) {
                return $this->convertUserData($user);
            });

        return $users;
    }

    public function convertUserData(User $user)
    {
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $username = $user->username;
        $email = $user->email;

        return (object) compact('firstName', 'lastName', 'username', 'email');
    }

    public function searchUserList(?string $keyword)
    {
        $users = User::where('username', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%");

        $users = $this->getUserList($users);

        return $users;
    }

    public function getCurrentUser(string $username): object
    {
        $user = User::where('username', $username)
            ->first();
        $user = $this->convertUserData($user);

        return $user;
    }
}
