<?php

namespace App\Services\Admin;

use App\Interfaces\StatusInterface;
use App\Interfaces\UserInterface;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagementService extends BaseService implements
    UserInterface,
    StatusInterface
{
    /**
     * @param User|\Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUserList($users)
    {
        $users = $users->paginate(10, ['*'], 'user')
            ->through(function ($user) {
                return $this->convertUserData($user);
            });

        return $users;
    }

    /**
     * @param User
     * @return object
     */
    public function convertUserData($user)
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
     * @param ?string
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function searchUserList($keyword)
    {
        $users = User::where(self::USERNAME_COLUMN, 'LIKE', "%{$keyword}%")
            ->orWhere(self::EMAIL_COLUMN, 'LIKE', "%{$keyword}%");

        $users = $this->getUserList($users);

        return $users;
    }

    /**
     * @param string
     * @return object
     */
    public function getCurrentUser($id)
    {
        $user = User::where(self::UUID_COLUMN, $id)
            ->first();
        $user = $this->convertUserData($user);

        return $user;
    }

    /**
     * @param string
     * @param array
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editUser($id, $validated)
    {
        try {
            DB::beginTransaction();

            $user = User::where(self::UUID_COLUMN, $id)
                ->first();

            $user->email = $validated[self::EMAIL_COLUMN] ?: $user->email;
            $user->first_name = $validated[self::FIRST_NAME_COLUMN] ?: $user->first_name;
            $user->last_name = $validated[self::LAST_NAME_COLUMN] ?: $user->last_name;
            $user->password = $validated[self::PASSWORD_COLUMN] ? Hash::make($validated[self::PASSWORD_COLUMN]) : $user->password;
            $user->save();

            DB::commit();
            $response = [
                'status' => self::STATUS_SUCCESS,
                'message' => 'User information has been updated successfully.',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.'
            ];

            return back()->with($response);
        }

        return back()->with($response);
    }

    /**
     * @param string
     * @param array
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteUser($id, $validated)
    {
        try {
            DB::beginTransaction();

            $currentAdmin = $this->getUser();
            if (!Hash::check($validated['confirm_password'], $currentAdmin->getAuthPassword())) {
                DB::rollBack();
                $response = [
                    'status' => self::STATUS_ERROR,
                    'message' => 'Password confirmation not match.',
                ];

                return back()->with($response);
            }

            $user = User::where(self::UUID_COLUMN, $id);
            $user->delete();

            DB::commit();
            $response = [
                'status' => self::STATUS_SUCCESS,
                'message' => 'User removed successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.'
            ];

            return back()->with($response);
        }

        return back()->with($response);
    }
}
