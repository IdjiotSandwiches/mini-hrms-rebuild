<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterService extends BaseService
{
    public function registerUser($validated)
    {
        try {
            DB::beginTransaction();

            $user = new User();
            $user->email = $validated['email'];
            $user->first_name = ucwords($validated['first_name']);
            $user->last_name = ucwords($validated['last_name']);
            $user->password = Hash::make($validated['password']);

            $username = strtolower($validated['first_name']) . strtolower($validated['last_name']);
            $suffix = 0;
            while(User::where('username', $username)->exists()) {
                $suffix++;
                $username .= $suffix;
            }

            $user->username = $username;
            $user->save();

            DB::commit();
            return redirect()->route('login')
                ->with([
                    'status' => 'success',
                    'message' => 'Account successfully created',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with([
                'status' => 'error',
                'message' => 'Invalid operation.',
            ]);
        }
    }
}
