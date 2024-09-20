<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagementService extends BaseService
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
        $users = User::where('username', 'LIKE', "%{$keyword}%")
            ->orWhere('email', 'LIKE', "%{$keyword}%");

        $users = $this->getUserList($users);

        return $users;
    }

    /**
     * @param string
     * @return object
     */
    public function getCurrentUser($id)
    {
        $user = User::where('uuid', $id)
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
            if (!Hash::check($validated['confirmation_password'], $currentAdmin->getAuthPassword())) {
                DB::rollBack();
                $response = [
                    'status' => 'error',
                    'message' => 'Password confirmation not match.',
                ];

                return back()->with($response);
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

            return back()->with($response);
        }

        return back()->with($response);
    }
}
