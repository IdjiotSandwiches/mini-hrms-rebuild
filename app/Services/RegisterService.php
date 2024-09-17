<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService extends BaseService
{
    public function registerUser($validated)
    {
        try {
            DB::beginTransaction();

            $user = new User();
            $user->uuid = Str::uuid();
            $user->email = $validated['email'];
            $user->first_name = ucwords($validated['first_name']);
            $user->last_name = ucwords($validated['last_name']);
            $user->password = Hash::make($validated['password']);
            $user->avatar = 'storage/avatars/default.png';

            $username = strtolower($validated['first_name']) . strtolower($validated['last_name']);
            $suffix = 0;
            while(User::where('username', $username)->exists()) {
                $suffix++;
                $username .= $suffix;
            }

            $user->username = $username;
            $user->save();

            DB::commit();
            $response = [
                'status' => 'success',
                'message' => 'Account successfully created',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'status' => 'error',
                'message' => 'Invalid operation.',
            ];

            return back()->with($response);
        }

        return redirect()->route('login')
            ->with($response);
    }
}
