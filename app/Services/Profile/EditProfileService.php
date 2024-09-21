<?php

namespace App\Services\Profile;

use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Interfaces\StatusInterface;
use App\Interfaces\UserInterface;

class EditProfileService extends BaseService implements
    UserInterface,
    StatusInterface
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

            if (is_null($validated[self::AVATAR_COLUMN]) &&
                is_null($validated[self::FIRST_NAME_COLUMN]) &&
                is_null($validated[self::LAST_NAME_COLUMN])) {
                    DB::rollBack();
                    $response = [
                        'status' => self::STATUS_ERROR,
                        'message' => 'At least one of the fields is required.'
                    ];

                    return back()->with($response);
                }

            $user = $this->getUser();
            $user->avatar = $validated[self::AVATAR_COLUMN] ?: $user->avatar;
            $user->first_name = $validated[self::FIRST_NAME_COLUMN] ?: $user->first_name;
            $user->last_name = $validated[self::LAST_NAME_COLUMN] ?: $user->last_name;
            $user->save();

            DB::commit();
            $response = [
                'status' => self::STATUS_SUCCESS,
                'message' => 'Your profile has been updated successfully.'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.',
            ];

            return back()->with($response);
        }

        return back()->with($response);
    }
}
