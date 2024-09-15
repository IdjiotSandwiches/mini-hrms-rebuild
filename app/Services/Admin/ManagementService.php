<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $id = $user->id;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $username = $user->username;
        $email = $user->email;

        return (object) compact(
            'id',
            'firstName',
            'lastName',
            'username',
            'email'
        );
    }

    public function searchUserList(?string $keyword)
    {
        $users = User::where('username', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%");

        $users = $this->getUserList($users);

        return $users;
    }

    public function getCurrentUser(int $id): object
    {
        $user = User::where('id', $id)
            ->first();
        $user = $this->convertUserData($user);

        return $user;
    }

    public function editUser(int $id, array $validated)
    {
        try {
            DB::beginTransaction();

            $user = User::where('id', $id)
                ->first();

            $user->email = $validated['email'] ?: $user->email;
            $user->first_name = $validated['first_name'] ?: $user->first_name;
            $user->last_name = $validated['last_name'] ?: $user->last_name;
            $user->password = $validated['password'] ? Hash::make($validated['password']) : $user->password;
            $user->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
}
