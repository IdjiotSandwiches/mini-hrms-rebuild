<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ManagementService extends BaseService
{
    /**
     * Method to paginate user list.
     *
     * @param User|Builder
     * @return LengthAwarePaginator
     */
    public function getUserList(User|Builder $users): LengthAwarePaginator
    {
        $users = $users->paginate(10, ['*'], 'user')
            ->through(function ($user) {
                return $this->convertUserData($user);
            });

        return $users;
    }

    /**
     * Method to map necessary user data.
     *
     * @param User
     * @return object
     */
    public function convertUserData(User $user): object
    {
        $id = $user->uuid;
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

    /**
     * Method to search user and return it as paginator.
     *
     * @param ?string
     * @return LengthAwarePaginator
     */
    public function searchUserList(?string $keyword): LengthAwarePaginator
    {
        $users = User::where('username', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%");

        $users = $this->getUserList($users);

        return $users;
    }

    /**
     * Method to get selected user.
     *
     * @param int
     * @return object
     */
    public function getCurrentUser(string $id): object
    {
        $user = User::where('uuid', $id)
            ->first();
        $user = $this->convertUserData($user);

        return $user;
    }

    /**
     * Method to update selected user information.
     *
     * @param int|array
     * @return array
     */
    public function editUser(string $id, array $validated): array
    {
        try {
            DB::beginTransaction();

            $user = User::where('uuid', $id)
                ->first();

            $user->email = $validated['email'] ?: $user->email;
            $user->first_name = $validated['first_name'] ?: $user->first_name;
            $user->last_name = $validated['last_name'] ?: $user->last_name;
            $user->password = $validated['password'] ? Hash::make($validated['password']) : $user->password;
            $user->save();

            DB::commit();
            $response = [
                'status' => 'success',
                'message' => 'User information has been updated successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => 'error',
                'message' => 'Invalid operation.'
            ];

            return $response;
        }

        return $response;
    }

    /**
     * Method to delete selected user.
     *
     * @param int|array
     * @return array
     */
    public function deleteUser(string $id, array $validated): array
    {
        try {
            DB::beginTransaction();

            $currentAdmin = $this->getUser();
            if (!Hash::check($validated['confirmation_password'], $currentAdmin->getAuthPassword())) {
                DB::rollBack();
                $response = [
                    'status' => 'error',
                    'message' => 'Password confirmation not match.',
                ];

                return $response;
            }

            $user = User::where('uuid', $id);
            $user->delete();

            DB::commit();
            $response = [
                'status' => 'success',
                'message' => 'User removed successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => 'error',
                'message' => 'Invalid operation.'
            ];

            return $response;
        }

        return $response;
    }
}
