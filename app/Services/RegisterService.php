<?php

namespace App\Services;

use App\Interfaces\StatusInterface;
use App\Interfaces\UserInterface;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService extends BaseService implements
    UserInterface,
    StatusInterface
{
    /**
     * @param array
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerUser($validated)
    {
        try {
            DB::beginTransaction();

            $user = new User();
            $user->uuid = Str::uuid();
            $user->email = $validated[self::EMAIL_COLUMN];
            $user->first_name = ucwords($validated[self::FIRST_NAME_COLUMN]);
            $user->last_name = ucwords($validated[self::LAST_NAME_COLUMN]);
            $user->password = Hash::make($validated[self::PASSWORD_COLUMN]);
            $user->avatar = 'storage/avatars/default.png';

            $username = strtolower($validated[self::FIRST_NAME_COLUMN]) . strtolower($validated[self::LAST_NAME_COLUMN]);
            $suffix = 0;
            while(User::where(self::USERNAME_COLUMN, $username)->exists()) {
                $suffix++;
                $username .= $suffix;
            }

            $user->username = $username;
            $user->save();

            DB::commit();
            $response = [
                'status' => self::STATUS_SUCCESS,
                'message' => 'Account successfully created',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => self::STATUS_ERROR,
                'message' => 'Invalid operation.',
            ];

            return back()->with($response);
        }

        return redirect()->route('login')
            ->with($response);
    }
}
