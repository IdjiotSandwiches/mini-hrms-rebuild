<?php

namespace App\Services;

use App\Interfaces\UserInterface;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class LoginService extends BaseService implements
    UserInterface
{
    /**
     * @param array
     * @return Admin|array|object|User|\Illuminate\Database\Eloquent\Model|null
     */
    public function attemptLogin($validated)
    {
        $user = User::where(self::EMAIL_COLUMN, $validated[self::EMAIL_COLUMN])->first() ??
            Admin::where(self::EMAIL_COLUMN, $validated[self::EMAIL_COLUMN])->first();

        if (!$user) {
            return $user;
        }

        if (!Hash::check($validated[self::PASSWORD_COLUMN], $user->password)) {
            return $user;
        }

        $isAdmin = $user instanceof Admin ? 'admin' : 'web';

        $response = [
            'user' => $user,
            'isAdmin' => $isAdmin
        ];

        return $response;
    }
}
