<?php

namespace App\Services\Profile;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;

class EditProfileService extends BaseService
{
    /**
     * @return object
     */
    public function getUserInformation()
    {
        $user = $this->getUser();
        $username = $user->username;
        $email = $user->email;
        $firstName = $user->first_name;
        $lastName = $user->last_name;
        $avatar = $user->avatar;

        return (object) compact(
            'username',
            'email',
            'firstName',
            'lastName',
            'avatar',
        );
    }

    /**
     * @param array
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile($validated)
    {
        try {
            DB::beginTransaction();

            if (is_null($validated['avatar']) &&
                is_null($validated['first_name']) &&
                is_null($validated['last_name'])) {
                    DB::rollBack();
                    $response = [
                        'status' => 'error',
                        'message' => 'At least one of the fields is required.'
                    ];

                    return back()->with($response);
                }

            $user = $this->getUser();
            $user->avatar = $validated['avatar'] ?: $user->avatar;
            $user->first_name = $validated['first_name'] ?: $user->first_name;
            $user->last_name = $validated['last_name'] ?: $user->last_name;
            $user->save();

            DB::commit();
            $response = [
                'status' => 'success',
                'message' => 'Your profile has been updated successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => 'error',
                'message' => 'Invalid operation.',
            ];

            return back()->with($response);
        }

        return back()->with($response);
    }
}
