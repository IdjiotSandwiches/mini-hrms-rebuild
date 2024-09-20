<?php

namespace App\Services;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class LoginService extends BaseService
{
    /**
     * @param array
     * @return Admin|array|object|User|\Illuminate\Database\Eloquent\Model|null
     */
    public function attemptLogin($validated)
    {
        $user = User::where('email', $validated['email'])->first() ??
            Admin::where('email', $validated['email'])->first();

        if (!$user) {
            return $user;
        }

        if (!Hash::check($validated['password'], $user->password)) {
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
